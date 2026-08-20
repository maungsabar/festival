<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Merchandise;
use App\Http\Controllers\Concerns\ScopesByAdminGender;

/**
 * Kelola Merchandise per kategori — admin_putra hanya boleh melihat & mengelola
 * merchandise gender 'Putra', admin_putri hanya 'Putri', superadmin bebas keduanya.
 * Otorisasi per-record (IDOR) untuk edit/update/destroy otomatis ditangani middleware
 * `gender.access` pada route group admin (lihat routes/web.php), sama seperti Rekening.
 */
class MerchandiseController extends Controller
{
    use ScopesByAdminGender;

    public function index()
    {
        $allowed = $this->allowedGenders();
        $role    = session('admin_user.role');

        $merchandises = Merchandise::whereIn('gender', $allowed)
            ->orderBy('gender')->orderBy('nama')
            ->get();

        return view('admin.merchandise.index', compact('merchandises', 'allowed', 'role'));
    }

    public function create()
    {
        return view('admin.merchandise.form', [
            'merchandise' => null,
            'allowed'     => $this->allowedGenders(),
        ]);
    }

    public function store(Request $request)
    {
        $allowed = $this->allowedGenders();

        $request->validate([
            'gender'     => ['required', 'in:' . implode(',', $allowed)],
            'nama'       => 'required|string|max:150',
            'deskripsi'  => 'nullable|string|max:1000',
            'harga'      => 'required|integer|min:0',
            'stok'       => 'nullable|integer|min:0',
            'gambar'     => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'aktif'      => 'nullable|boolean',
        ]);

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $ext  = $file->extension() ?: $file->getClientOriginalExtension();
            $gambarName = time() . '_merchandise_' . uniqid() . '.' . $ext;
            $file->move(storage_path('app/public/merchandise'), $gambarName);
        }

        Merchandise::create([
            // PENGUNCIAN: sama seperti Rekening — admin_putra/admin_putri cuma punya
            // satu opsi gender (allowedGenders() dibatasi 1 nilai), jadi request->gender
            // di-override paksa. Hanya superadmin (2 opsi) yang boleh memilih via form.
            'gender'    => count($allowed) === 1 ? $allowed[0] : $request->gender,
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga'     => $request->harga,
            'gambar'    => $gambarName,
            'stok'      => $request->stok !== '' ? $request->stok : null,
            'aktif'     => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.merchandise.index')->with('success', 'Merchandise ditambahkan.');
    }

    public function edit(Merchandise $merchandise)
    {
        return view('admin.merchandise.form', [
            'merchandise' => $merchandise,
            'allowed'     => $this->allowedGenders(),
        ]);
    }

    public function update(Request $request, Merchandise $merchandise)
    {
        $allowed = $this->allowedGenders();

        $request->validate([
            'gender'     => ['required', 'in:' . implode(',', $allowed)],
            'nama'       => 'required|string|max:150',
            'deskripsi'  => 'nullable|string|max:1000',
            'harga'      => 'required|integer|min:0',
            'stok'       => 'nullable|integer|min:0',
            'gambar'     => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'aktif'      => 'nullable|boolean',
        ]);

        $gambarName = $merchandise->gambar;
        if ($request->hasFile('gambar')) {
            if ($merchandise->gambar && file_exists(storage_path('app/public/merchandise/' . $merchandise->gambar))) {
                @unlink(storage_path('app/public/merchandise/' . $merchandise->gambar));
            }
            $file = $request->file('gambar');
            $ext  = $file->extension() ?: $file->getClientOriginalExtension();
            $gambarName = time() . '_merchandise_' . uniqid() . '.' . $ext;
            $file->move(storage_path('app/public/merchandise'), $gambarName);
        }

        $merchandise->update([
            // PENGUNCIAN: sama seperti store() — gender tidak boleh diubah
            // admin_putra/admin_putri jadi kategori lawan lewat request manipulation.
            'gender'    => count($allowed) === 1 ? $allowed[0] : $request->gender,
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga'     => $request->harga,
            'gambar'    => $gambarName,
            'stok'      => $request->stok !== '' ? $request->stok : null,
            'aktif'     => $request->boolean('aktif', false),
        ]);

        return redirect()->route('admin.merchandise.index')->with('success', 'Merchandise diperbarui.');
    }

    public function destroy(Merchandise $merchandise)
    {
        if ($merchandise->gambar && file_exists(storage_path('app/public/merchandise/' . $merchandise->gambar))) {
            @unlink(storage_path('app/public/merchandise/' . $merchandise->gambar));
        }
        $merchandise->delete();
        return redirect()->route('admin.merchandise.index')->with('success', 'Merchandise dihapus.');
    }
}
