<?php
namespace App\Providers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void {
        if (!app()->runningInConsole() && request()) {
            config(['app.url' => request()->getSchemeAndHttpHost()]);
        }
        Model::shouldBeStrict(!$this->app->isProduction());
        Paginator::useTailwind();
        View::composer('*', function($view) {
            try {
                $view->with([
                    'festivalName'              => Setting::get('festival_name','Festival Sekolah'),
                    'festivalYear'              => Setting::get('festival_year',date('Y')),
                    'festivalTagline'           => Setting::get('festival_tagline','Kompetisi Antar Pelajar'),
                    'festivalLogo'              => Setting::get('festival_logo'),
                    'festivalLogoHero'          => Setting::get('festival_logo_hero'),
                    'festivalHeroText'          => Setting::get('festival_hero_text','Tunjukkan bakat terbaikmu, raih prestasi dan banggakan sekolahmu di festival bergengsi ini.'),
                    'countdownDate'             => Setting::get('countdown_date'),
                    'pendaftaranStatus'         => Setting::get('pendaftaran_status','dibuka'),
                    'pendaftaranBelumTeks'      => Setting::get('pendaftaran_belum_teks','Pendaftaran Belum Dibuka'),
                    'pendaftaranDibukaTeks'     => Setting::get('pendaftaran_dibuka_teks','Pendaftaran Resmi Dibuka'),
                    'pendaftaranDitutupTeks'    => Setting::get('pendaftaran_ditutup_teks','Pendaftaran Resmi Ditutup'),
                    'heroBgImage'               => Setting::get('hero_bg_image'),
                    'heroBgColor'               => Setting::get('hero_bg_color','#0a1628'),
                    'heroBgOverlayOpacity'      => Setting::get('hero_bg_overlay_opacity','70'),
                    'socialInstagram'           => Setting::get('social_instagram'),
                    'socialTiktok'              => Setting::get('social_tiktok'),
                    'socialYoutube'             => Setting::get('social_youtube'),
                    'socialFacebook'            => Setting::get('social_facebook'),
                    'contactPhone'              => Setting::get('contact_phone'),
                    'contactEmail'              => Setting::get('contact_email'),
                    'contactWhatsapp'           => Setting::get('contact_whatsapp'),
                    'contactWaPutra1'           => Setting::get('contact_whatsapp_putra_1'),
                    'contactWaPutra1Nama'       => Setting::get('contact_whatsapp_putra_1_nama','HUMAS'),
                    'contactWaPutra2'           => Setting::get('contact_whatsapp_putra_2'),
                    'contactWaPutra2Nama'       => Setting::get('contact_whatsapp_putra_2_nama','BENDAHARA'),
                    'contactWaPutri1'           => Setting::get('contact_whatsapp_putri_1'),
                    'contactWaPutri1Nama'       => Setting::get('contact_whatsapp_putri_1_nama','HUMAS'),
                    'contactWaPutri2'           => Setting::get('contact_whatsapp_putri_2'),
                    'contactWaPutri2Nama'       => Setting::get('contact_whatsapp_putri_2_nama','BENDAHARA'),
                ]);
            } catch(\Exception $e) {
                $view->with([
                    'festivalName'=>'Festival Sekolah','festivalYear'=>date('Y'),
                    'festivalTagline'=>'Kompetisi Antar Pelajar','festivalLogo'=>null,
                    'festivalLogoHero'=>null,'festivalHeroText'=>'Tunjukkan bakat terbaikmu.',
                    'countdownDate'=>null,'pendaftaranStatus'=>'dibuka',
                    'pendaftaranBelumTeks'=>'Pendaftaran Belum Dibuka',
                    'pendaftaranDibukaTeks'=>'Pendaftaran Resmi Dibuka',
                    'pendaftaranDitutupTeks'=>'Pendaftaran Resmi Ditutup',
                    'heroBgImage'=>null,'heroBgColor'=>'#0a1628','heroBgOverlayOpacity'=>'70',
                    'socialInstagram'=>null,'socialTiktok'=>null,'socialYoutube'=>null,'socialFacebook'=>null,
                    'contactPhone'=>null,'contactEmail'=>null,'contactWhatsapp'=>null,
                    'contactWaPutra1'=>null,'contactWaPutra1Nama'=>'HUMAS',
                    'contactWaPutra2'=>null,'contactWaPutra2Nama'=>'BENDAHARA',
                    'contactWaPutri1'=>null,'contactWaPutri1Nama'=>'HUMAS',
                    'contactWaPutri2'=>null,'contactWaPutri2Nama'=>'BENDAHARA',
                ]);
            }
        });
    }
}

