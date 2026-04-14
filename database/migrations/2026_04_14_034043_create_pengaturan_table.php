<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->string('key', 50)->primary();
            $table->string('value', 100);
            $table->string('keterangan', 150)->nullable();
        });
        DB::table('pengaturan')->insert([
            'key' => 'batas_laporan_aktif',
            'value' => '2',
            'keterangan' => 'Maksimal laporan aktif per siswa',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
