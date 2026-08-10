<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 10)->unique();
            $table->string('nama');
            $table->enum('gender', ['Putra', 'Putri']);
            $table->string('nama_sekolah');
            $table->text('alamat_sekolah');
            $table->foreignId('id_lomba')->constrained('lombas')->restrictOnDelete();
            $table->string('file_kartu_siswa');
            $table->string('file_bukti_pembayaran');
            $table->enum('status_verifikasi', ['Belum', 'Terverifikasi', 'Ditolak'])->default('Belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
