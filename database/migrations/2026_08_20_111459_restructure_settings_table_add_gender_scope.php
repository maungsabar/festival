<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Restrukturisasi `settings` dari flat key-value menjadi key-value BER-GENDER,
 * supaya satu key (mis. "hero_bg_image") bisa dipakai ulang untuk 3 scope
 * berbeda: Global (landing page), Putra, dan Putri — tanpa perlu membakar
 * gender ke dalam nama key seperti sebelumnya ("hero_putra_bg_image").
 *
 * Konvensi nilai gender DISAMAKAN dengan tabel lombas/pendaftars yang sudah
 * ada ('Putra'/'Putri', bukan huruf kecil) + 'Global' untuk setting bersama —
 * supaya tidak ada dua konvensi casing berbeda untuk konsep yang sama di
 * proyek yang sama. Model App\Models\Setting menormalisasi input apa pun
 * ('putra', 'Putra', 'PUTRA') ke bentuk ini secara otomatis.
 */
return new class extends Migration
{
    /** key lama (gender dibakar ke nama) -> [gender baru, key baru murni] */
    private function legacyGenderedKeyMap(): array
    {
        return [
            'contact_whatsapp_putra_1'      => ['Putra', 'contact_whatsapp_1'],
            'contact_whatsapp_putra_1_nama' => ['Putra', 'contact_whatsapp_1_nama'],
            'contact_whatsapp_putra_2'      => ['Putra', 'contact_whatsapp_2'],
            'contact_whatsapp_putra_2_nama' => ['Putra', 'contact_whatsapp_2_nama'],
            'contact_whatsapp_putri_1'      => ['Putri', 'contact_whatsapp_1'],
            'contact_whatsapp_putri_1_nama' => ['Putri', 'contact_whatsapp_1_nama'],
            'contact_whatsapp_putri_2'      => ['Putri', 'contact_whatsapp_2'],
            'contact_whatsapp_putri_2_nama' => ['Putri', 'contact_whatsapp_2_nama'],
        ];
    }

    public function up(): void
    {
        // 1) Tambah kolom gender, default 'Global' supaya SEMUA baris lama yang
        //    belum bicara soal gender otomatis jadi setting bersama (tidak ada
        //    data yang "hilang" konteksnya).
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->enum('gender', ['Global', 'Putra', 'Putri'])->default('Global')->after('id');
        });

        // 2) Normalisasi baris yang gendernya masih "dibakar" ke nama key lama
        //    (mis. contact_whatsapp_putra_1) menjadi kombinasi gender + key murni.
        foreach ($this->legacyGenderedKeyMap() as $oldKey => [$gender, $newKey]) {
            DB::table('settings')->where('key', $oldKey)->update([
                'gender' => $gender,
                'key'    => $newKey,
            ]);
        }

        // 3) Kunci integritas: satu key hanya boleh unik PER gender (bukan global
        //    lagi seperti skema lama) — inilah yang bikin key "hero_bg_image"
        //    boleh punya 3 baris berbeda (Global/Putra/Putri) sekaligus.
        Schema::table('settings', function (Blueprint $table) {
            $table->unique(['gender', 'key']);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['gender', 'key']);
        });

        foreach ($this->legacyGenderedKeyMap() as $oldKey => [$gender, $newKey]) {
            DB::table('settings')->where('gender', $gender)->where('key', $newKey)->update([
                'gender' => 'Global',
                'key'    => $oldKey,
            ]);
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->unique('key');
        });
    }
};
