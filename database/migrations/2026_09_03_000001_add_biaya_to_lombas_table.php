<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->unsignedBigInteger('biaya')->default(0)->after('kuota');
        });
    }

    public function down(): void
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->dropColumn('biaya');
        });
    }
};
