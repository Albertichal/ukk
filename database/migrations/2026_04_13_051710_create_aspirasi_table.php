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
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->id('id_aspirasi');
            $table->foreignId('id_pelaporan')->constrained('input_aspirasi', 'id_pelaporan')->onDelete('cascade');
            $table->unsignedInteger('id_kategori');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
            $table->enum('status', ['Diassign', 'Proses', 'Tidak_Mampu', 'Sedang_Dikerjakan', 'Selesai', 'Ditolak'])->default('Diassign');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->text('feedback')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirasi');
    }
};
