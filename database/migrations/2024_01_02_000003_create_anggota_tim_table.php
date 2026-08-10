<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('anggota_tim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(1);
            $table->string('nisn',10)->nullable();
            $table->string('nama');
            $table->string('kelas')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('anggota_tim'); }
};
