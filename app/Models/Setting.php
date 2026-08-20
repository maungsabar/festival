<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const GLOBAL = 'Global';
    public const PUTRA  = 'Putra';
    public const PUTRI  = 'Putri';

    protected $fillable = ['gender', 'key', 'value'];

    /**
     * Normalisasi input gender apa pun ('putra', 'PUTRA', 'Putra', null, '')
     * ke salah satu dari 3 nilai baku di kolom `gender`. Dibuat toleran biar
     * pemanggil (controller/blade) tidak perlu ingat casing yang "benar".
     */
    protected static function normalizeGender(?string $gender): string
    {
        return match (strtolower(trim((string) $gender))) {
            'putra' => self::PUTRA,
            'putri' => self::PUTRI,
            default => self::GLOBAL,
        };
    }

    /**
     * Ambil satu nilai setting.
     *
     * PENTING soal urutan parameter: $default tetap di posisi ke-2 (bukan
     * $gender) supaya seluruh pemanggilan LAMA `Setting::get($key, $default)`
     * yang sudah tersebar di AppServiceProvider/Controller (skema key-value
     * sebelumnya) TETAP JALAN tanpa perubahan apa pun — otomatis membaca
     * scope 'Global'. Setting gender-scoped ditambahkan lewat parameter
     * ke-3 (opsional):
     *
     *   Setting::get('festival_name', 'Festival Sekolah');           // Global, kompatibel dg kode lama
     *   Setting::get('whatsapp_number', null, 'putra');              // scope Putra
     *   Setting::get('whatsapp_number', '08xxx', $genderLabel);      // scope dinamis + default
     */
    public static function get(string $key, mixed $default = null, ?string $gender = null): mixed
    {
        $gender = self::normalizeGender($gender);

        return Cache::remember("setting_{$gender}_{$key}", 3600, function () use ($key, $default, $gender) {
            $row = static::where('gender', $gender)->where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    /**
     * Simpan satu nilai setting (create kalau belum ada, update kalau sudah).
     *
     *   Setting::set('festival_name', 'Festival Baru');              // Global
     *   Setting::set('status_pendaftaran', 'dibuka', 'putri');       // scope Putri
     */
    public static function set(string $key, mixed $value, ?string $gender = null): void
    {
        $gender = self::normalizeGender($gender);

        static::updateOrCreate(
            ['gender' => $gender, 'key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_{$gender}_{$key}");
    }

    /**
     * Ambil SEMUA setting milik satu gender sekaligus, sebagai [key => value].
     * Dipakai controller untuk auto-load seluruh key gender=Putra/Putri
     * ketika admin_putra/admin_putri membuka halaman pengaturan mereka —
     * tidak perlu tahu daftar key-nya satu per satu di awal.
     */
    public static function allFor(?string $gender): array
    {
        $gender = self::normalizeGender($gender);

        return static::where('gender', $gender)->pluck('value', 'key')->all();
    }

    /**
     * Hapus cache untuk sekumpulan key pada satu gender sekaligus — berguna
     * setelah operasi bulk save (loop banyak field dalam satu request).
     */
    public static function forgetCache(array $keys, ?string $gender = null): void
    {
        $gender = self::normalizeGender($gender);
        foreach ($keys as $key) {
            Cache::forget("setting_{$gender}_{$key}");
        }
    }
}
