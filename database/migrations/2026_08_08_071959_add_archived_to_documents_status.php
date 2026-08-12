<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan Raw Query (DB::statement) adalah cara paling aman dan anti-error 
        // untuk memodifikasi kolom ENUM di MySQL tanpa perlu menginstall doctrine/dbal.
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('pending', 'processing', 'ready', 'failed', 'archived') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PENTING: Saat rollback, kita kembalikan ke ENUM aslinya.
        // Jika ada data yang terlanjur 'archived', kita ubah dulu ke 'ready' agar tidak error saat ENUM dikecilkan.
        DB::statement("UPDATE documents SET status = 'ready' WHERE status = 'archived'");
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('pending', 'processing', 'ready', 'failed') NOT NULL DEFAULT 'pending'");
    }
};