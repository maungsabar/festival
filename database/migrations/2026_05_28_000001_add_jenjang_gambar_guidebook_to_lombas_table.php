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
        Schema::table('lombas', function (Blueprint $table) {
            $table->enum('jenjang', ['SMP', 'SMA', 'UMUM'])->default('SMA')->after('gender');
            $table->string('gambar')->nullable()->after('tampil_pemenang');
            $table->string('file_guidebook')->nullable()->after('gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->dropColumn(['jenjang', 'gambar', 'file_guidebook']);
        });
    }
};
