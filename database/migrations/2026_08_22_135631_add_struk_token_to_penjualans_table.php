<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Token acak (bukan id berurutan) dipakai di URL struk publik, supaya
            // struk pembeli lain tidak bisa ditebak/di-enumerasi lewat ganti angka di URL.
            $table->string('struk_token', 40)->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn('struk_token');
        });
    }
};
