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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['Putra', 'Putri']); // diambil dari gender produk, bukan input pembeli
            $table->foreignId('merchandise_id')->constrained()->cascadeOnDelete();
            $table->string('nama_pembeli');
            $table->string('hp_pembeli');
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('harga_satuan'); // snapshot harga produk saat order dibuat
            $table->unsignedInteger('total_harga');
            $table->string('bukti_transfer');
            $table->string('status')->default('Menunggu Verifikasi'); // Menunggu Verifikasi | Dikonfirmasi | Dibatalkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
