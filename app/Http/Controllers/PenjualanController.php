<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\Merchandise;
use App\Http\Controllers\Concerns\ScopesByAdminGender;

/**
 * Kelola "Data Penjualan" — order online merchandise yang dibuat pembeli lewat
 * PublicController::orderMerchandise(). Otorisasi per-record (IDOR) untuk
 * show/status/destroy sudah ditangani middleware `gender.access` (lihat
 * routes/web.php) — sama seperti Pendaftar/Rekening/Merchandise, karena
 * model ini punya kolom `gender`.
 */
class PenjualanController extends Controller
{
    use ScopesByAdminGender;

    public function index(Request $request)
    {
        $allowed = $this->allowedGenders();
        $role    = session('admin_user.role');

        $q = Penjualan::with('merchandise')->whereIn('gender', $allowed);

        if ($role === 'superadmin' && $request->filled('gender') && in_array($request->gender, ['Putra', 'Putri'])) {
            $q->where('gender', $request->gender);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn ($qq) => $qq->where('nama_pembeli', 'like', "%$s%")->orWhere('hp_pembeli', 'like', "%$s%"));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $penjualans = $q->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.penjualan.index', compact('penjualans', 'allowed', 'role'));
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load('merchandise');
        return view('admin.penjualan.show', compact('penjualan'));
    }

    public function updateStatus(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'status' => ['required', 'in:Menunggu Verifikasi,Dikonfirmasi,Dibatalkan'],
        ]);

        $old = $penjualan->status;
        $new = $request->status;

        if ($old !== $new) {
            DB::transaction(function () use ($penjualan, $old, $new) {
                $merchandise = Merchandise::lockForUpdate()->find($penjualan->merchandise_id);

                if ($merchandise && $merchandise->stok !== null) {
                    // Batalkan (dari status aktif manapun) -> stok dikembalikan.
                    if ($new === 'Dibatalkan' && $old !== 'Dibatalkan') {
                        $merchandise->increment('stok', $penjualan->jumlah);
                    // Batal-membatalkan (dari Dibatalkan ke status aktif lagi) -> stok dikurangi lagi.
                    } elseif ($old === 'Dibatalkan' && $new !== 'Dibatalkan') {
                        $merchandise->decrement('stok', $penjualan->jumlah);
                    }
                    // Transisi antar status non-Dibatalkan (mis. Menunggu -> Dikonfirmasi)
                    // tidak menyentuh stok sama sekali — sudah dikurangi saat order dibuat.
                }

                $penjualan->update(['status' => $new]);
            });
        }

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function destroy(Penjualan $penjualan)
    {
        if ($penjualan->bukti_transfer && file_exists(storage_path('app/public/bukti_transfer_merchandise/' . $penjualan->bukti_transfer))) {
            @unlink(storage_path('app/public/bukti_transfer_merchandise/' . $penjualan->bukti_transfer));
        }
        $penjualan->delete();
        return redirect()->route('admin.penjualan.index')->with('success', 'Data penjualan dihapus.');
    }
}
