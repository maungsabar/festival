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
        Schema::table('rekenings', function (Blueprint $table) {
            // 'pendaftaran' (rekening form pendaftaran lomba) atau 'merchandise'
            // (rekening khusus pembelian merchandise) — default 'pendaftaran' supaya
            // seluruh baris lama otomatis terklasifikasi tanpa mengubah perilaku
            // fitur pendaftaran yang sudah berjalan.
            $table->string('konteks', 20)->default('pendaftaran')->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekenings', function (Blueprint $table) {
            $table->dropColumn('konteks');
        });
    }
};
