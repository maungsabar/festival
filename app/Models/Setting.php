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
        foreach (['festival_name','festival_year','festival_tagline','festival_logo',
                  'festival_hero_text','countdown_date','social_instagram','social_tiktok',
                  'social_youtube','social_facebook','contact_phone','contact_email','contact_whatsapp'] as $k) {
            Cache::forget("setting_{$k}");
        }
    }
}
