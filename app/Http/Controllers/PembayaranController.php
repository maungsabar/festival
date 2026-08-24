<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;
use App\Http\Controllers\Concerns\ScopesByAdminGender;

/**
 * Kelola "Rekening Pembayaran" (Nama Bank, Nomor Rekening, Atas Nama) yang
 * ditampilkan ke pendaftar publik saat mengisi formulir pendaftaran —
 * lihat PublicController::showForm() dan resources/views/public/daftar.blade.php.
 *
 * Aturan akses:
 * - admin_putra hanya boleh melihat & mengelola rekening gender 'Putra'.
 * - admin_putri hanya boleh melihat & mengelola rekening gender 'Putri'.
 * - superadmin bebas mengelola & memilih gender keduanya.
 *
 * Otorisasi per-record (IDOR) untuk edit/update/destroy sudah ditangani
 * otomatis oleh middleware `gender.access` pada route group admin (lihat
 * routes/web.php) — middleware itu menolak 403 begitu {rekening} hasil
 * route-model-binding punya gender di luar allowedGenders() admin yang login.
 */
class PembayaranController extends Controller
{
    use ScopesByAdminGender;

    public function index()
    {
        $allowed   = $this->allowedGenders();
        $role      = session('admin_user.role');
        $rekenings = Rekening::whereIn('gender', $allowed)
            ->where('konteks', 'pendaftaran')
            ->orderBy('gender')->orderBy('nama_bank')
            ->get();

        return view('admin.pembayaran.index', compact('rekenings', 'allowed', 'role'));
    }

    public function create()
    {
        return view('admin.pembayaran.form', [
            'rekening' => null,
            'allowed'  => $this->allowedGenders(),
        ]);
    }

    public function store(Request $request)
    {
        $allowed = $this->allowedGenders();

        $request->validate([
            'gender'         => ['required', 'in:' . implode(',', $allowed)],
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:150',
            'aktif'          => 'nullable|boolean',
        ]);

        Rekening::create([
            // PENGUNCIAN: admin_putra/admin_putri cuma punya satu opsi gender
            // (allowedGenders() sudah dibatasi ke satu nilai), jadi request->gender
            // di-override paksa ke nilai itu — tidak pernah dipercaya mentah-mentah.
            // Hanya superadmin (2 opsi) yang boleh memilih via form.
            'gender'         => count($allowed) === 1 ? $allowed[0] : $request->gender,
            'konteks'        => 'pendaftaran', // controller ini KHUSUS rekening pendaftaran lomba
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => $request->atas_nama,
            'aktif'          => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Rekening pembayaran ditambahkan.');
    }

    public function edit(Rekening $rekening)
    {
        return view('admin.pembayaran.form', [
            'rekening' => $rekening,
            'allowed'  => $this->allowedGenders(),
        ]);
    }

    public function update(Request $request, Rekening $rekening)
    {
        $allowed = $this->allowedGenders();

        $request->validate([
            'gender'         => ['required', 'in:' . implode(',', $allowed)],
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:150',
            'aktif'          => 'nullable|boolean',
        ]);

        $rekening->update([
            // PENGUNCIAN: sama seperti store() — gender tidak boleh diubah
            // admin_putra/admin_putri jadi kategori lawan lewat request manipulation.
            'gender'         => count($allowed) === 1 ? $allowed[0] : $request->gender,
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => $request->atas_nama,
            'aktif'          => $request->boolean('aktif', false),
        ]);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Rekening pembayaran diperbarui.');
    }

    public function destroy(Rekening $rekening)
    {
        $rekening->delete();
        return redirect()->route('admin.pembayaran.index')->with('success', 'Rekening pembayaran dihapus.');
    }
}
