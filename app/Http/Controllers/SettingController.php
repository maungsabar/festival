<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        if (session('admin_user.role') !== 'superadmin') abort(403);
        $s = fn($k,$d='') => Setting::get($k,$d);

        $heroBgImagesRaw = $s('hero_bg_images');
        $heroBgImagesList = [];
        if ($heroBgImagesRaw) {
            $heroBgImagesList = is_array($heroBgImagesRaw) ? $heroBgImagesRaw : (json_decode($heroBgImagesRaw, true) ?: []);
        }
        if (empty($heroBgImagesList)) {
            $single = $s('hero_bg_image');
            if ($single) $heroBgImagesList = [$single];
        }

        $settings = [
            'festival_name'              => $s('festival_name','Festival Sekolah'),
            'festival_year'              => $s('festival_year', date('Y')),
            'festival_tagline'           => $s('festival_tagline'),
            'festival_logo'              => $s('festival_logo'),
            'festival_logo_hero'         => $s('festival_logo_hero'),
            'festival_hero_text'         => $s('festival_hero_text','Tunjukkan bakat terbaikmu, raih prestasi dan banggakan sekolahmu di festival bergengsi ini.'),
            'countdown_date'             => $s('countdown_date'),
            'pendaftaran_status'         => $s('pendaftaran_status','dibuka'),
            'pendaftaran_belum_teks'     => $s('pendaftaran_belum_teks','Pendaftaran Belum Dibuka'),
            'pendaftaran_dibuka_teks'    => $s('pendaftaran_dibuka_teks','Pendaftaran Resmi Dibuka'),
            'pendaftaran_ditutup_teks'   => $s('pendaftaran_ditutup_teks','Pendaftaran Resmi Ditutup'),
            'hero_bg_image'              => $s('hero_bg_image'),
            'hero_bg_images_list'        => $heroBgImagesList,
            'hero_bg_color'              => $s('hero_bg_color','#0a1628'),
            'hero_bg_overlay_opacity'    => $s('hero_bg_overlay_opacity','70'),
            'social_instagram'           => $s('social_instagram'),
            'social_tiktok'              => $s('social_tiktok'),
            'social_youtube'             => $s('social_youtube'),
            'social_facebook'            => $s('social_facebook'),
            'contact_phone'              => $s('contact_phone'),
            'contact_email'              => $s('contact_email'),
            'contact_whatsapp'           => $s('contact_whatsapp'),
            'contact_whatsapp_putra_1'      => $s('contact_whatsapp_putra_1'),
            'contact_whatsapp_putra_1_nama' => $s('contact_whatsapp_putra_1_nama','HUMAS'),
            'contact_whatsapp_putra_2'      => $s('contact_whatsapp_putra_2'),
            'contact_whatsapp_putra_2_nama' => $s('contact_whatsapp_putra_2_nama','BENDAHARA'),
            'contact_whatsapp_putri_1'      => $s('contact_whatsapp_putri_1'),
            'contact_whatsapp_putri_1_nama' => $s('contact_whatsapp_putri_1_nama','HUMAS'),
            'contact_whatsapp_putri_2'      => $s('contact_whatsapp_putri_2'),
            'contact_whatsapp_putri_2_nama' => $s('contact_whatsapp_putri_2_nama','BENDAHARA'),
        ];
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (session('admin_user.role') !== 'superadmin') abort(403);
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
            'pendaftaran_status'         => 'nullable|in:belum,dibuka,ditutup',
            'pendaftaran_belum_teks'     => 'nullable|string|max:200',
            'pendaftaran_dibuka_teks'    => 'nullable|string|max:200',
            'pendaftaran_ditutup_teks'   => 'nullable|string|max:200',
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

        // Simpan setting teks/nilai biasa
        $textKeys = [
            'festival_name','festival_year','festival_tagline','festival_hero_text','countdown_date',
            'pendaftaran_status','pendaftaran_belum_teks','pendaftaran_dibuka_teks','pendaftaran_ditutup_teks',
            'hero_bg_color','hero_bg_overlay_opacity',
            'social_instagram','social_tiktok','social_youtube','social_facebook',
            'contact_phone','contact_email','contact_whatsapp',
            'contact_whatsapp_putra_1','contact_whatsapp_putra_1_nama',
            'contact_whatsapp_putra_2','contact_whatsapp_putra_2_nama',
            'contact_whatsapp_putri_1','contact_whatsapp_putri_1_nama',
            'contact_whatsapp_putri_2','contact_whatsapp_putri_2_nama',
        ];
        foreach ($textKeys as $k) {
            Setting::set($k, $request->input($k,''));
        }

        // Logo utama (header/sidebar)
        if ($request->hasFile('festival_logo')) {
            $old = Setting::get('festival_logo');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if(file_exists($p)) unlink($p); }
            $file = $request->file('festival_logo');
            $ext  = $file->extension() ?: $file->getClientOriginalExtension();
            $name = 'logo_'.time().'.'.$ext;
            $file->move(storage_path('app/public/logos'), $name);
            Setting::set('festival_logo', $name);
        }
        if ($request->boolean('remove_logo')) {
            $old = Setting::get('festival_logo');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if(file_exists($p)) unlink($p); }
            Setting::set('festival_logo','');
        }

        // Logo hero (tampil di bagian hero beranda)
        if ($request->hasFile('festival_logo_hero')) {
            $old = Setting::get('festival_logo_hero');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if(file_exists($p)) unlink($p); }
            $file = $request->file('festival_logo_hero');
            $ext  = $file->extension() ?: $file->getClientOriginalExtension();
            $name = 'logo_hero_'.time().'.'.$ext;
            $file->move(storage_path('app/public/logos'), $name);
            Setting::set('festival_logo_hero', $name);
        }
        if ($request->boolean('remove_logo_hero')) {
            $old = Setting::get('festival_logo_hero');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if(file_exists($p)) unlink($p); }
            Setting::set('festival_logo_hero','');
        }

        // Hero Background Images (Multiple Files & Gallery)
        $rawImages = Setting::get('hero_bg_images');
        $existingBgList = [];
        if ($rawImages) {
            $existingBgList = is_array($rawImages) ? $rawImages : (json_decode($rawImages, true) ?: []);
        }
        if (empty($existingBgList)) {
            $single = Setting::get('hero_bg_image');
            if ($single) $existingBgList = [$single];
        }

        // Hapus gambar terpilih jika ada yang dicentang hapus
        if ($request->has('delete_hero_bg_images')) {
            $toDelete = (array) $request->input('delete_hero_bg_images', []);
            foreach ($toDelete as $filename) {
                $path = storage_path("app/public/hero_bg/{$filename}");
                if (file_exists($path)) {
                    @unlink($path);
                }
                $existingBgList = array_values(array_filter($existingBgList, fn($item) => $item !== $filename));
            }
        }

        // Hapus semua jika checkbox remove_hero_bg dicentang
        if ($request->boolean('remove_hero_bg')) {
            foreach ($existingBgList as $filename) {
                $path = storage_path("app/public/hero_bg/{$filename}");
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
            $existingBgList = [];
        }

        // Upload multiple gambar baru jika ada
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

        // Single file upload fallback jika diupload via hero_bg_image
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

        return redirect()->route('admin.settings')->with('success','Pengaturan berhasil disimpan.');
    }
}

