<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add 'Menunggu' alongside 'Diassign' so both are valid
        DB::statement("ALTER TABLE aspirasi MODIFY COLUMN status ENUM('Menunggu','Diassign','Proses','Tidak_Mampu','Sedang_Dikerjakan','Selesai','Ditolak') DEFAULT 'Menunggu'");
        // Step 2: Migrate any existing 'Diassign' rows to 'Menunggu'
        DB::statement("UPDATE aspirasi SET status = 'Menunggu' WHERE status = 'Diassign'");
        // Step 3: Remove 'Diassign' from enum
        DB::statement("ALTER TABLE aspirasi MODIFY COLUMN status ENUM('Menunggu','Proses','Tidak_Mampu','Sedang_Dikerjakan','Selesai','Ditolak') DEFAULT 'Menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE aspirasi MODIFY COLUMN status ENUM('Diassign','Proses','Tidak_Mampu','Sedang_Dikerjakan','Selesai','Ditolak') DEFAULT 'Diassign'");
    }
};
