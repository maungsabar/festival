<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Merchandise;
use App\Models\Rekening;
use App\Models\Setting;
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

    /**
     * Skema field "Setup" (tampilan header katalog publik + pilihan nomor WA
     * pemesanan) — per gender, sama gaya seperti SettingController::genderedFieldsSchema().
     * Disimpan lewat Setting::get/set (key generik, tidak perlu tabel baru).
     */
    private function setupFieldsSchema(): array
    {
        return [
            ['key' => 'merchandise_hero_image',        'label' => 'Gambar Header',            'type' => 'file', 'rules' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048', 'folder' => 'merchandise_header', 'default' => null],
            ['key' => 'merchandise_judul',              'label' => 'Judul Header',              'type' => 'text', 'rules' => 'nullable|string|max:150', 'default' => null],
            ['key' => 'merchandise_tagline',             'label' => 'Tagline Header',            'type' => 'text', 'rules' => 'nullable|string|max:200', 'default' => null],
            ['key' => 'merchandise_whatsapp_pilihan',    'label' => 'Nomor WA Dipakai',          'type' => 'text', 'rules' => 'required|in:1,2', 'default' => '1'],
        ];
    }

    /**
     * Resolve gender aktif untuk halaman Setup dari query string ?gender=,
     * dikunci ke allowedGenders() admin yang login (pola sama seperti
     * PublicController::showForm() mengunci gender dari URL).
     */
    private function resolveSetupGender(Request $request): string
    {
        $allowed = $this->allowedGenders();
        $query   = $request->query('gender');
        $label   = $query === 'putri' ? 'Putri' : ($query === 'putra' ? 'Putra' : null);
        return ($label && in_array($label, $allowed, true)) ? $label : $allowed[0];
    }

    public function setup(Request $request)
    {
        $allowed = $this->allowedGenders();
        $gender  = $this->resolveSetupGender($request);
        $schema  = $this->setupFieldsSchema();

        $settings = [];
        foreach ($schema as $f) {
            $settings[$f['key']] = Setting::get($f['key'], $f['default'], $gender);
        }

        $waRef = [
            '1' => ['nomor' => Setting::get('contact_whatsapp_1', null, $gender), 'nama' => Setting::get('contact_whatsapp_1_nama', null, $gender)],
            '2' => ['nomor' => Setting::get('contact_whatsapp_2', null, $gender), 'nama' => Setting::get('contact_whatsapp_2_nama', null, $gender)],
        ];

        $rekenings = Rekening::where('gender', $gender)
            ->where('untuk_merchandise', true)
            ->orderBy('nama_bank')
            ->get();

        // Rekening yang sudah ada di Kelola Pembayaran (untuk_pendaftaran) tapi BELUM
        // dipakai di merchandise — ditawarkan sebagai pilihan cepat "pakai yang sudah
        // ada" supaya admin tidak perlu input ulang nomor rekening yang sama.
        $rekeningsPilihan = Rekening::where('gender', $gender)
            ->where('untuk_pendaftaran', true)
            ->where('untuk_merchandise', false)
            ->orderBy('nama_bank')
            ->get();

        return view('admin.merchandise.setup', compact('settings', 'gender', 'allowed', 'waRef', 'rekenings', 'rekeningsPilihan'));
    }

    public function updateSetup(Request $request)
    {
        $allowed = $this->allowedGenders();
        $gender  = (in_array($request->input('gender'), $allowed, true)) ? $request->input('gender') : $allowed[0];
        $schema  = $this->setupFieldsSchema();

        $rules = [];
        foreach ($schema as $f) {
            $rules[$f['key']] = $f['rules'];
        }
        $request->validate($rules);

        foreach ($schema as $f) {
            $key = $f['key'];

            if ($f['type'] === 'file') {
                if ($request->hasFile($key)) {
                    $old = Setting::get($key, null, $gender);
                    if ($old) {
                        $p = storage_path("app/public/{$f['folder']}/{$old}");
                        if (file_exists($p)) @unlink($p);
                    }
                    $file = $request->file($key);
                    $ext  = $file->extension() ?: $file->getClientOriginalExtension();
                    $name = $key . '_' . strtolower($gender) . '_' . time() . '.' . $ext;
                    $file->move(storage_path("app/public/{$f['folder']}"), $name);
                    Setting::set($key, $name, $gender);
                } elseif ($request->boolean("remove_{$key}")) {
                    $old = Setting::get($key, null, $gender);
                    if ($old) {
                        $p = storage_path("app/public/{$f['folder']}/{$old}");
                        if (file_exists($p)) @unlink($p);
                    }
                    Setting::set($key, '', $gender);
                }
                continue;
            }

            Setting::set($key, $request->input($key, ''), $gender);
        }

        return redirect()->route('admin.merchandise.setup', ['gender' => strtolower($gender)])
            ->with('success', 'Setup merchandise kategori ' . $gender . ' berhasil disimpan.');
    }

    public function rekeningStore(Request $request)
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
            'gender'            => count($allowed) === 1 ? $allowed[0] : $request->gender,
            'untuk_pendaftaran' => false,
            'untuk_merchandise' => true,
            'nama_bank'         => $request->nama_bank,
            'nomor_rekening'    => $request->nomor_rekening,
            'atas_nama'         => $request->atas_nama,
            'aktif'             => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.merchandise.setup', ['gender' => strtolower(count($allowed) === 1 ? $allowed[0] : $request->gender)])
            ->with('success', 'Rekening merchandise ditambahkan.');
    }

    /**
     * "Pilih dari Kelola Pembayaran" — pakai rekening yang SUDAH ADA (bukan input
     * ulang), cukup nyalakan flag untuk_merchandise pada baris yang sama. Tidak ada
     * duplikasi data: kalau nomor rekening diubah nanti, otomatis ikut berubah di
     * kedua tempat karena sumbernya satu baris yang sama.
     */
    public function rekeningAttach(Rekening $rekening)
    {
        $rekening->update(['untuk_merchandise' => true]);
        return redirect()->route('admin.merchandise.setup', ['gender' => strtolower($rekening->gender)])
            ->with('success', 'Rekening "' . $rekening->nama_bank . '" sekarang dipakai untuk merchandise.');
    }

    public function rekeningUpdate(Request $request, Rekening $rekening)
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
            'gender'         => count($allowed) === 1 ? $allowed[0] : $request->gender,
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => $request->atas_nama,
            'aktif'          => $request->boolean('aktif', false),
        ]);

        return redirect()->route('admin.merchandise.setup', ['gender' => strtolower($rekening->gender)])
            ->with('success', 'Rekening merchandise diperbarui.');
    }

    public function rekeningDestroy(Rekening $rekening)
    {
        $gender = $rekening->gender;

        // Kalau rekening ini JUGA dipakai di Kelola Pembayaran, jangan hapus baris-nya
        // (akan ikut hilang dari form pendaftaran) — cukup lepas dari konteks merchandise.
        if ($rekening->untuk_pendaftaran) {
            $rekening->update(['untuk_merchandise' => false]);
            return redirect()->route('admin.merchandise.setup', ['gender' => strtolower($gender)])
                ->with('success', 'Rekening dilepas dari daftar merchandise (masih dipakai di Kelola Pembayaran).');
        }

        $rekening->delete();
        return redirect()->route('admin.merchandise.setup', ['gender' => strtolower($gender)])
            ->with('success', 'Rekening merchandise dihapus.');
    }
}
