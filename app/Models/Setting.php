<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key','value'];
    public static function get(string $key, mixed $default = null): mixed {
        return Cache::remember("setting_{$key}", 3600, function() use ($key,$default) {
            $s = static::where('key',$key)->first();
            return $s ? $s->value : $default;
        });
    }
    public static function set(string $key, mixed $value): void {
        static::updateOrCreate(['key'=>$key],['value'=>$value]);
        Cache::forget("setting_{$key}");
    }
    public static function clearCache(): void {
        foreach ([
            'festival_name','festival_year','festival_tagline','festival_logo',
            'festival_logo_hero','festival_hero_text','countdown_date',
            'social_instagram','social_tiktok','social_youtube','social_facebook',
            'contact_phone','contact_email','contact_whatsapp',
            'pendaftaran_status','pendaftaran_belum_teks','pendaftaran_dibuka_teks','pendaftaran_ditutup_teks',
            'hero_bg_image','hero_bg_color','hero_bg_overlay_opacity',
            'contact_whatsapp_putra_1','contact_whatsapp_putra_2',
            'contact_whatsapp_putri_1','contact_whatsapp_putri_2',
            'contact_whatsapp_putra_1_nama','contact_whatsapp_putra_2_nama',
            'contact_whatsapp_putri_1_nama','contact_whatsapp_putri_2_nama',
        ] as $k) {
            Cache::forget("setting_{$k}");
        }
    }
}

