<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->string('nama_pendamping')->nullable()->after('alamat_sekolah');
            $table->string('hp_pendamping')->nullable()->after('nama_pendamping');
            $table->string('hp_peserta')->nullable()->after('hp_pendamping');
            $table->string('link_twibbon')->nullable()->after('hp_peserta');
            $table->string('nama_tim')->nullable()->after('link_twibbon');
        });
    }
    public function down(): void {
        Schema::table('pendaftars', function(Blueprint $t) {
            $t->dropColumn(['nama_pendamping','hp_pendamping','hp_peserta','link_twibbon','nama_tim']);
        });
    }
};
