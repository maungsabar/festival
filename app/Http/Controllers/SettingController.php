<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Controllers\Concerns\ScopesByAdminGender;

class SettingController extends Controller
{
    use ScopesByAdminGender;

    /**
     * Skema field yang berlaku PER-GENDER (Putra & Putri masing-masing punya
     * baris sendiri di tabel settings, dengan key yang SAMA). Satu sumber
     * kebenaran ini dipakai baik oleh halaman superadmin (digabung ke array
     * $settings lama, lihat buildLegacySettingsArray()) maupun halaman
     * ringkas admin_putra/admin_putri (indexGender()/updateGender()) —
     * jadi kalau suatu saat mau nambah field baru, cukup tambah satu baris
     * di sini, tidak perlu menyentuh dua tempat.
     */
    private function genderedFieldsSchema(): array
    {
        return [
            ['key' => 'status_pendaftaran',       'label' => 'Status Pendaftaran',        'type' => 'radio', 'rules' => 'nullable|in:belum,dibuka,ditutup', 'default' => 'dibuka'],
            ['key' => 'teks_belum',               'label' => 'Teks "Belum"',              'type' => 'text',  'rules' => 'nullable|string|max:200', 'default' => 'Pendaftaran Belum Dibuka'],
            ['key' => 'teks_dibuka',              'label' => 'Teks "Dibuka"',             'type' => 'text',  'rules' => 'nullable|string|max:200', 'default' => 'Pendaftaran Resmi Dibuka'],
            ['key' => 'teks_ditutup',             'label' => 'Teks "Ditutup"',            'type' => 'text',  'rules' => 'nullable|string|max:200', 'default' => 'Pendaftaran Resmi Ditutup'],
            ['key' => 'hero_bg_image',           'label' => 'Gambar Hero',              'type' => 'file',  'rules' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120', 'folder' => 'hero_bg', 'default' => null],
            ['key' => 'hero_bg_color',            'label' => 'Warna Latar Hero',          'type' => 'color', 'rules' => 'nullable|string|max:20', 'default' => '#1d4ed8'],
            ['key' => 'hero_bg_overlay_opacity',  'label' => 'Opacity Overlay',           'type' => 'range', 'rules' => 'nullable|integer|min:0|max:100', 'default' => '70'],
            ['key' => 'logo_tahunan',             'label' => 'Logo Tahunan / Event',      'type' => 'file',  'rules' => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048', 'folder' => 'logos', 'default' => null],
            ['key' => 'judul_hero',               'label' => 'Judul Hero',                'type' => 'text',  'rules' => 'nullable|string|max:150', 'default' => null],
            ['key' => 'tagline_tahunan',          'label' => 'Tagline Tahunan / Event',   'type' => 'text',  'rules' => 'nullable|string|max:200', 'default' => null],
            ['key' => 'countdown',                'label' => 'Batas Waktu Countdown',     'type' => 'date',  'rules' => 'nullable|date', 'default' => null],
            ['key' => 'contact_whatsapp_1',       'label' => 'WhatsApp 1',                'type' => 'text',  'rules' => 'nullable|string|max:20', 'default' => null],
            ['key' => 'contact_whatsapp_1_nama',  'label' => 'Label WhatsApp 1',          'type' => 'text',  'rules' => 'nullable|string|max:50', 'default' => 'HUMAS'],
            ['key' => 'contact_whatsapp_2',       'label' => 'WhatsApp 2',                'type' => 'text',  'rules' => 'nullable|string|max:20', 'default' => null],
            ['key' => 'contact_whatsapp_2_nama',  'label' => 'Label WhatsApp 2',          'type' => 'text',  'rules' => 'nullable|string|max:50', 'default' => 'BENDAHARA'],
            ['key' => 'social_instagram',         'label' => 'Instagram',                 'type' => 'url',   'rules' => 'nullable|url|max:300', 'default' => null],
            ['key' => 'social_tiktok',             'label' => 'TikTok',                   'type' => 'url',   'rules' => 'nullable|url|max:300', 'default' => null],
            ['key' => 'social_youtube',            'label' => 'YouTube',                  'type' => 'url',   'rules' => 'nullable|url|max:300', 'default' => null],
            ['key' => 'social_facebook',           'label' => 'Facebook',                 'type' => 'url',   'rules' => 'nullable|url|max:300', 'default' => null],
        ];
    }

    public function index()
    {
        $role = session('admin_user.role');
        if (!in_array($role, ['superadmin', 'admin_putra', 'admin_putri'], true)) abort(403);

        if ($role !== 'superadmin') {
            // admin_putra/admin_putri: auto-load SEMUA key milik gender mereka —
            // tidak perlu daftar key manual, cukup baca skema + gender aktif.
            // Dibentuk jadi array $settings[key => value] (bukan daftar objek field)
            // supaya Blade-nya bisa ditulis identik gaya admin/settings.blade.php
            // milik superadmin ($settings['hero_bg_image'], dst) — desainnya
            // direplikasi persis, cuma sumber datanya beda scope gender.
            $gender = $this->allowedGenders()[0]; // 'Putra' atau 'Putri' saja untuk role ini
            $settings = [];
            foreach ($this->genderedFieldsSchema() as $f) {
                $settings[$f['key']] = Setting::get($f['key'], $f['default'], $gender);
            }

            return view('admin.settings-gender', compact('settings', 'gender'));
        }

        $settings = $this->buildLegacySettingsArray();
        return view('admin.settings', compact('settings'));
    }

    /**
     * Menyusun array $settings dengan NAMA KEY LAMA (hero_putra_bg_image, dst)
     * supaya view admin/settings.blade.php yang sudah ada tidak perlu diubah
     * sama sekali — hanya SUMBER datanya yang sekarang baca dari kombinasi
     * gender+key baru, bukan lagi dari satu key panjang gabungan.
     */
    private function buildLegacySettingsArray(): array
    {
        $s = fn ($k, $d = '') => Setting::get($k, $d);          // scope Global (kompatibel dg kode lama)
        $g = fn ($k, $gender, $d = null) => Setting::get($k, $d, $gender); // scope Putra/Putri

        $heroBgImagesRaw = $s('hero_bg_images');
        $heroBgImagesList = [];
        if ($heroBgImagesRaw) {
            $heroBgImagesList = is_array($heroBgImagesRaw) ? $heroBgImagesRaw : (json_decode($heroBgImagesRaw, true) ?: []);
        }
        if (empty($heroBgImagesList)) {
            $single = $s('hero_bg_image');
            if ($single) $heroBgImagesList = [$single];
        }

        return [
            'festival_name'              => $s('festival_name', 'Festival Sekolah'),
            'festival_year'              => $s('festival_year', date('Y')),
            'festival_tagline'           => $s('festival_tagline'),
            'festival_logo'              => $s('festival_logo'),
            'festival_logo_hero'         => $s('festival_logo_hero'),
            'festival_hero_text'         => $s('festival_hero_text', 'Tunjukkan bakat terbaikmu, raih prestasi dan banggakan sekolahmu di festival bergengsi ini.'),
            'countdown_date'             => $s('countdown_date'),
            'pendaftaran_status'         => $s('pendaftaran_status', 'dibuka'),
            'pendaftaran_belum_teks'     => $s('pendaftaran_belum_teks', 'Pendaftaran Belum Dibuka'),
            'pendaftaran_dibuka_teks'    => $s('pendaftaran_dibuka_teks', 'Pendaftaran Resmi Dibuka'),
            'pendaftaran_ditutup_teks'   => $s('pendaftaran_ditutup_teks', 'Pendaftaran Resmi Ditutup'),
            'hero_bg_image'              => $s('hero_bg_image'),
            'hero_bg_images_list'        => $heroBgImagesList,
            'hero_bg_color'              => $s('hero_bg_color', '#0a1628'),
            'hero_bg_overlay_opacity'    => $s('hero_bg_overlay_opacity', '70'),
            'hero_putra_bg_image'            => $g('hero_bg_image', 'Putra'),
            'hero_putra_bg_color'            => $g('hero_bg_color', 'Putra', '#1d4ed8'),
            'hero_putra_bg_overlay_opacity'  => $g('hero_bg_overlay_opacity', 'Putra', '70'),
            'hero_putri_bg_image'            => $g('hero_bg_image', 'Putri'),
            'hero_putri_bg_color'            => $g('hero_bg_color', 'Putri', '#db2777'),
            'hero_putri_bg_overlay_opacity'  => $g('hero_bg_overlay_opacity', 'Putri', '70'),
            'logo_tahunan_putra'             => $g('logo_tahunan', 'Putra'),
            'tagline_tahunan_putra'          => $g('tagline_tahunan', 'Putra'),
            'countdown_putra'                => $g('countdown', 'Putra'),
            'logo_tahunan_putri'             => $g('logo_tahunan', 'Putri'),
            'tagline_tahunan_putri'          => $g('tagline_tahunan', 'Putri'),
            'countdown_putri'                => $g('countdown', 'Putri'),
            'social_instagram'           => $s('social_instagram'),
            'social_tiktok'              => $s('social_tiktok'),
            'social_youtube'             => $s('social_youtube'),
            'social_facebook'            => $s('social_facebook'),
            'contact_phone'              => $s('contact_phone'),
            'contact_email'              => $s('contact_email'),
            'contact_whatsapp'           => $s('contact_whatsapp'),
            'contact_whatsapp_putra_1'      => $g('contact_whatsapp_1', 'Putra'),
            'contact_whatsapp_putra_1_nama' => $g('contact_whatsapp_1_nama', 'Putra', 'HUMAS'),
            'contact_whatsapp_putra_2'      => $g('contact_whatsapp_2', 'Putra'),
            'contact_whatsapp_putra_2_nama' => $g('contact_whatsapp_2_nama', 'Putra', 'BENDAHARA'),
            'contact_whatsapp_putri_1'      => $g('contact_whatsapp_1', 'Putri'),
            'contact_whatsapp_putri_1_nama' => $g('contact_whatsapp_1_nama', 'Putri', 'HUMAS'),
            'contact_whatsapp_putri_2'      => $g('contact_whatsapp_2', 'Putri'),
            'contact_whatsapp_putri_2_nama' => $g('contact_whatsapp_2_nama', 'Putri', 'BENDAHARA'),
        ];
    }

    public function update(Request $request)
    {
        $role = session('admin_user.role');
        if (!in_array($role, ['superadmin', 'admin_putra', 'admin_putri'], true)) abort(403);

        if ($role !== 'superadmin') {
            return $this->updateGender($request);
        }

        $request->validate([
            'festival_name'              => 'required|string|max:100',
            'festival_year'              => 'required|digits:4|integer|min:2000|max:2099',
            'festival_tagline'           => 'nullable|string|max:200',
            'festival_hero_text'         => 'nullable|string|max:500',
            'countdown_date'             => 'nullable|date',
            'festival_logo'              => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'festival_logo_hero'         => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'hero_bg_image'              => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'hero_bg_images.*'           => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'delete_hero_bg_images'      => 'nullable|array',
            'delete_hero_bg_images.*'    => 'nullable|string',
            'hero_bg_color'              => 'nullable|string|max:20',
            'hero_bg_overlay_opacity'    => 'nullable|integer|min:0|max:100',
            'hero_putra_bg_image'            => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'hero_putra_bg_color'            => 'nullable|string|max:20',
            'hero_putra_bg_overlay_opacity'  => 'nullable|integer|min:0|max:100',
            'hero_putri_bg_image'            => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'hero_putri_bg_color'            => 'nullable|string|max:20',
            'hero_putri_bg_overlay_opacity'  => 'nullable|integer|min:0|max:100',
            'logo_tahunan_putra'             => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'tagline_tahunan_putra'          => 'nullable|string|max:200',
            'countdown_putra'                => 'nullable|date',
            'logo_tahunan_putri'             => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'tagline_tahunan_putri'          => 'nullable|string|max:200',
            'countdown_putri'                => 'nullable|date',
            // Status Pendaftaran Global SENGAJA tidak divalidasi/disimpan lagi di sini —
            // sudah dinonaktifkan di admin/settings.blade.php, dipindah ke Pengaturan
            // Kategori Putra/Putri masing-masing (lihat genderedFieldsSchema() & updateGender()).
            'social_instagram'           => 'nullable|url|max:300',
            'social_tiktok'              => 'nullable|url|max:300',
            'social_youtube'             => 'nullable|url|max:300',
            'social_facebook'            => 'nullable|url|max:300',
            'contact_phone'              => 'nullable|string|max:20',
            'contact_email'              => 'nullable|email|max:100',
            'contact_whatsapp'           => 'nullable|string|max:20',
            'contact_whatsapp_putra_1'      => 'nullable|string|max:20',
            'contact_whatsapp_putra_1_nama' => 'nullable|string|max:50',
            'contact_whatsapp_putra_2'      => 'nullable|string|max:20',
            'contact_whatsapp_putra_2_nama' => 'nullable|string|max:50',
            'contact_whatsapp_putri_1'      => 'nullable|string|max:20',
            'contact_whatsapp_putri_1_nama' => 'nullable|string|max:50',
            'contact_whatsapp_putri_2'      => 'nullable|string|max:20',
            'contact_whatsapp_putri_2_nama' => 'nullable|string|max:50',
        ]);

        // Simpan setting teks/nilai biasa — scope Global (2 argumen, kompatibel dg kode lama)
        $textKeys = [
            'festival_name','festival_year','festival_tagline','festival_hero_text','countdown_date',
            'hero_bg_color','hero_bg_overlay_opacity',
            'social_instagram','social_tiktok','social_youtube','social_facebook',
            'contact_phone','contact_email','contact_whatsapp',
        ];
        foreach ($textKeys as $k) {
            Setting::set($k, $request->input($k, ''));
        }

        // Setting teks ber-gender — scope Putra & Putri (parameter ke-3), key sudah dinormalisasi
        $genderedTextKeys = [
            'hero_bg_color'           => 'hero_putra_bg_color',
            'hero_bg_overlay_opacity' => 'hero_putra_bg_overlay_opacity',
            'tagline_tahunan'         => 'tagline_tahunan_putra',
            'countdown'               => 'countdown_putra',
            'contact_whatsapp_1'      => 'contact_whatsapp_putra_1',
            'contact_whatsapp_1_nama' => 'contact_whatsapp_putra_1_nama',
            'contact_whatsapp_2'      => 'contact_whatsapp_putra_2',
            'contact_whatsapp_2_nama' => 'contact_whatsapp_putra_2_nama',
        ];
        foreach ($genderedTextKeys as $newKey => $inputName) {
            Setting::set($newKey, $request->input($inputName, ''), 'Putra');
        }
        $genderedTextKeys = [
            'hero_bg_color'           => 'hero_putri_bg_color',
            'hero_bg_overlay_opacity' => 'hero_putri_bg_overlay_opacity',
            'tagline_tahunan'         => 'tagline_tahunan_putri',
            'countdown'               => 'countdown_putri',
            'contact_whatsapp_1'      => 'contact_whatsapp_putri_1',
            'contact_whatsapp_1_nama' => 'contact_whatsapp_putri_1_nama',
            'contact_whatsapp_2'      => 'contact_whatsapp_putri_2',
            'contact_whatsapp_2_nama' => 'contact_whatsapp_putri_2_nama',
        ];
        foreach ($genderedTextKeys as $newKey => $inputName) {
            Setting::set($newKey, $request->input($inputName, ''), 'Putri');
        }

        // Logo utama (header/sidebar) — Global
        if ($request->hasFile('festival_logo')) {
            $old = Setting::get('festival_logo');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if (file_exists($p)) unlink($p); }
            $file = $request->file('festival_logo');
            $ext  = $file->extension() ?: $file->getClientOriginalExtension();
            $name = 'logo_'.time().'.'.$ext;
            $file->move(storage_path('app/public/logos'), $name);
            Setting::set('festival_logo', $name);
        }
        if ($request->boolean('remove_logo')) {
            $old = Setting::get('festival_logo');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if (file_exists($p)) unlink($p); }
            Setting::set('festival_logo', '');
        }

        // Logo hero (tampil di bagian hero beranda) — Global
        if ($request->hasFile('festival_logo_hero')) {
            $old = Setting::get('festival_logo_hero');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if (file_exists($p)) unlink($p); }
            $file = $request->file('festival_logo_hero');
            $ext  = $file->extension() ?: $file->getClientOriginalExtension();
            $name = 'logo_hero_'.time().'.'.$ext;
            $file->move(storage_path('app/public/logos'), $name);
            Setting::set('festival_logo_hero', $name);
        }
        if ($request->boolean('remove_logo_hero')) {
            $old = Setting::get('festival_logo_hero');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if (file_exists($p)) unlink($p); }
            Setting::set('festival_logo_hero', '');
        }

        // File upload ber-gender (logo tahunan & hero background per kategori)
        $this->handleGenderedFileUpload($request, 'logo_tahunan_putra', 'logo_tahunan', 'Putra', 'logos', 'logo_tahunan_putra', 'remove_logo_tahunan_putra');
        $this->handleGenderedFileUpload($request, 'logo_tahunan_putri', 'logo_tahunan', 'Putri', 'logos', 'logo_tahunan_putri', 'remove_logo_tahunan_putri');
        $this->handleGenderedFileUpload($request, 'hero_putra_bg_image', 'hero_bg_image', 'Putra', 'hero_bg', 'hero_putra', 'remove_hero_putra_bg');
        $this->handleGenderedFileUpload($request, 'hero_putri_bg_image', 'hero_bg_image', 'Putri', 'hero_bg', 'hero_putri', 'remove_hero_putri_bg');

        // Hero Background Images (Multiple Files & Gallery) — Global
        $rawImages = Setting::get('hero_bg_images');
        $existingBgList = [];
        if ($rawImages) {
            $existingBgList = is_array($rawImages) ? $rawImages : (json_decode($rawImages, true) ?: []);
        }
        if (empty($existingBgList)) {
            $single = Setting::get('hero_bg_image');
            if ($single) $existingBgList = [$single];
        }

        if ($request->has('delete_hero_bg_images')) {
            $toDelete = (array) $request->input('delete_hero_bg_images', []);
            foreach ($toDelete as $filename) {
                $path = storage_path("app/public/hero_bg/{$filename}");
                if (file_exists($path)) @unlink($path);
                $existingBgList = array_values(array_filter($existingBgList, fn ($item) => $item !== $filename));
            }
        }

        if ($request->boolean('remove_hero_bg')) {
            foreach ($existingBgList as $filename) {
                $path = storage_path("app/public/hero_bg/{$filename}");
                if (file_exists($path)) @unlink($path);
            }
            $existingBgList = [];
        }

        if ($request->hasFile('hero_bg_images')) {
            foreach ($request->file('hero_bg_images') as $file) {
                if ($file && $file->isValid()) {
                    $ext  = $file->extension() ?: $file->getClientOriginalExtension();
                    $name = 'hero_bg_'.time().'_'.uniqid().'.'.$ext;
                    $file->move(storage_path('app/public/hero_bg'), $name);
                    $existingBgList[] = $name;
                }
            }
        }

        if ($request->hasFile('hero_bg_image')) {
            $file = $request->file('hero_bg_image');
            if ($file && $file->isValid()) {
                $ext  = $file->extension() ?: $file->getClientOriginalExtension();
                $name = 'hero_bg_'.time().'_'.uniqid().'.'.$ext;
                $file->move(storage_path('app/public/hero_bg'), $name);
                $existingBgList[] = $name;
            }
        }

        $existingBgList = array_values(array_unique($existingBgList));
        Setting::set('hero_bg_images', json_encode($existingBgList));
        Setting::set('hero_bg_image', !empty($existingBgList) ? $existingBgList[0] : '');

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Halaman pengaturan ringkas untuk admin_putra/admin_putri: loop atas
     * genderedFieldsSchema(), validasi dinamis, lalu updateOrCreate per
     * kombinasi gender+key — persis pola yang diminta ("loop semua input
     * form, simpan pakai updateOrCreate berdasarkan gender+key").
     */
    private function updateGender(Request $request)
    {
        $gender = $this->allowedGenders()[0];
        $schema = $this->genderedFieldsSchema();

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
                    $name = $key.'_'.strtolower($gender).'_'.time().'.'.$ext;
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

        return redirect()->route('admin.settings')->with('success', 'Pengaturan kategori ' . $gender . ' berhasil disimpan.');
    }

    private function handleGenderedFileUpload(Request $request, string $inputName, string $key, string $gender, string $folder, string $filenamePrefix, string $removeInputName): void
    {
        if ($request->hasFile($inputName)) {
            $old = Setting::get($key, null, $gender);
            if ($old) {
                $p = storage_path("app/public/{$folder}/{$old}");
                if (file_exists($p)) @unlink($p);
            }
            $file = $request->file($inputName);
            $ext  = $file->extension() ?: $file->getClientOriginalExtension();
            $name = $filenamePrefix.'_'.time().'.'.$ext;
            $file->move(storage_path("app/public/{$folder}"), $name);
            Setting::set($key, $name, $gender);
            return;
        }

        if ($request->boolean($removeInputName)) {
            $old = Setting::get($key, null, $gender);
            if ($old) {
                $p = storage_path("app/public/{$folder}/{$old}");
                if (file_exists($p)) @unlink($p);
            }
            Setting::set($key, '', $gender);
        }
    }
}
