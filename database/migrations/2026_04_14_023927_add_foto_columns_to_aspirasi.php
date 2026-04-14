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
        Schema::table('aspirasi', function (Blueprint $table) {
            $table->string('foto_laporan')->nullable()->after('alasan_tolak');
            $table->string('foto_selesai')->nullable()->after('foto_laporan');
        });
    }

    public function down(): void
    {
        Schema::table('aspirasi', function (Blueprint $table) {
            $table->dropColumn(['foto_laporan', 'foto_selesai']);
        });
    }
};
