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
        $settings = [
            'festival_name'     => $s('festival_name','Festival Sekolah'),
            'festival_year'     => $s('festival_year', date('Y')),
            'festival_tagline'  => $s('festival_tagline'),
            'festival_logo'     => $s('festival_logo'),
            'festival_hero_text'=> $s('festival_hero_text','Tunjukkan bakat terbaikmu, raih prestasi dan banggakan sekolahmu di festival bergengsi ini.'),
            'countdown_date'    => $s('countdown_date'),
            'social_instagram'  => $s('social_instagram'),
            'social_tiktok'     => $s('social_tiktok'),
            'social_youtube'    => $s('social_youtube'),
            'social_facebook'   => $s('social_facebook'),
            'contact_phone'     => $s('contact_phone'),
            'contact_email'     => $s('contact_email'),
            'contact_whatsapp'  => $s('contact_whatsapp'),
        ];
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (session('admin_user.role') !== 'superadmin') abort(403);
        $request->validate([
            'festival_name'     => 'required|string|max:100',
            'festival_year'     => 'required|digits:4|integer|min:2000|max:2099',
            'festival_tagline'  => 'nullable|string|max:200',
            'festival_hero_text'=> 'nullable|string|max:500',
            'countdown_date'    => 'nullable|date',
            'festival_logo'     => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'social_instagram'  => 'nullable|url|max:300',
            'social_tiktok'     => 'nullable|url|max:300',
            'social_youtube'    => 'nullable|url|max:300',
            'social_facebook'   => 'nullable|url|max:300',
            'contact_phone'     => 'nullable|string|max:20',
            'contact_email'     => 'nullable|email|max:100',
            'contact_whatsapp'  => 'nullable|string|max:20',
        ]);

        foreach (['festival_name','festival_year','festival_tagline','festival_hero_text','countdown_date',
                  'social_instagram','social_tiktok','social_youtube','social_facebook',
                  'contact_phone','contact_email','contact_whatsapp'] as $k) {
            Setting::set($k, $request->input($k,''));
        }

        if ($request->hasFile('festival_logo')) {
            $old = Setting::get('festival_logo');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if(file_exists($p)) unlink($p); }
            $file = $request->file('festival_logo');
            $name = 'logo_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(storage_path('app/public/logos'), $name);
            Setting::set('festival_logo', $name);
        }
        if ($request->boolean('remove_logo')) {
            $old = Setting::get('festival_logo');
            if ($old) { $p = storage_path("app/public/logos/{$old}"); if(file_exists($p)) unlink($p); }
            Setting::set('festival_logo','');
        }

        return redirect()->route('admin.settings')->with('success','Pengaturan berhasil disimpan.');
    }
}
