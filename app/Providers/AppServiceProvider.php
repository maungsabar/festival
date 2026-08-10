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
        Model::shouldBeStrict(!$this->app->isProduction());
        Paginator::useTailwind();
        View::composer('*', function($view) {
            try {
                $view->with([
                    'festivalName'     => Setting::get('festival_name','Festival Sekolah'),
                    'festivalYear'     => Setting::get('festival_year',date('Y')),
                    'festivalTagline'  => Setting::get('festival_tagline','Kompetisi Antar Pelajar'),
                    'festivalLogo'     => Setting::get('festival_logo'),
                    'festivalHeroText' => Setting::get('festival_hero_text','Tunjukkan bakat terbaikmu, raih prestasi dan banggakan sekolahmu di festival bergengsi ini.'),
                    'countdownDate'    => Setting::get('countdown_date'),
                    'socialInstagram'  => Setting::get('social_instagram'),
                    'socialTiktok'     => Setting::get('social_tiktok'),
                    'socialYoutube'    => Setting::get('social_youtube'),
                    'socialFacebook'   => Setting::get('social_facebook'),
                    'contactPhone'     => Setting::get('contact_phone'),
                    'contactEmail'     => Setting::get('contact_email'),
                    'contactWhatsapp'  => Setting::get('contact_whatsapp'),
                ]);
            } catch(\Exception $e) {
                $view->with(['festivalName'=>'Festival Sekolah','festivalYear'=>date('Y'),'festivalTagline'=>'Kompetisi Antar Pelajar','festivalLogo'=>null,'festivalHeroText'=>'Tunjukkan bakat terbaikmu.','countdownDate'=>null,'socialInstagram'=>null,'socialTiktok'=>null,'socialYoutube'=>null,'socialFacebook'=>null,'contactPhone'=>null,'contactEmail'=>null,'contactWhatsapp'=>null]);
            }
        });
    }
}
