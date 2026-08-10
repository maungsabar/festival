<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('lombas', function (Blueprint $table) {
            $table->enum('tipe',['single','team'])->default('single')->after('gender');
            $table->unsignedInteger('min_anggota')->default(1)->after('tipe');
            $table->unsignedInteger('max_anggota')->default(1)->after('min_anggota');
            $table->unsignedInteger('kuota')->nullable()->after('max_anggota');
            $table->string('pemenang')->nullable()->after('kuota');
            $table->boolean('tampil_pemenang')->default(false)->after('pemenang');
        });
    }
    public function down(): void {
        Schema::table('lombas', function(Blueprint $t) {
            $t->dropColumn(['tipe','min_anggota','max_anggota','kuota','pemenang','tampil_pemenang']);
        });
    }
};
