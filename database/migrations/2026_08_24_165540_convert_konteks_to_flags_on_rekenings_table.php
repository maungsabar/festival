<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ganti kolom `konteks` (string tunggal, satu rekening cuma bisa untuk SATU
     * konteks) jadi dua flag independen — supaya satu rekening bisa dipakai di
     * Kelola Pembayaran (pendaftaran) DAN Setup Merchandise sekaligus, tanpa
     * duplikasi data (satu baris = satu sumber kebenaran nomor rekening).
     */
    public function up(): void
    {
        Schema::table('rekenings', function (Blueprint $table) {
            $table->boolean('untuk_pendaftaran')->default(true)->after('konteks');
            $table->boolean('untuk_merchandise')->default(false)->after('untuk_pendaftaran');
        });

        DB::table('rekenings')->where('konteks', 'merchandise')->update([
            'untuk_pendaftaran' => false,
            'untuk_merchandise' => true,
        ]);
        DB::table('rekenings')->where('konteks', 'pendaftaran')->update([
            'untuk_pendaftaran' => true,
            'untuk_merchandise' => false,
        ]);

        Schema::table('rekenings', function (Blueprint $table) {
            $table->dropColumn('konteks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekenings', function (Blueprint $table) {
            $table->string('konteks', 20)->default('pendaftaran')->after('gender');
        });

        DB::table('rekenings')->where('untuk_merchandise', true)->update(['konteks' => 'merchandise']);
        DB::table('rekenings')->where('untuk_merchandise', false)->update(['konteks' => 'pendaftaran']);

        Schema::table('rekenings', function (Blueprint $table) {
            $table->dropColumn(['untuk_pendaftaran', 'untuk_merchandise']);
        });
    }
};
