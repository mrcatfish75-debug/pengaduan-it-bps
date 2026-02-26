<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ================================
        // INDEX UNTUK LAPORAN PENGADUAN
        // ================================
        Schema::table('laporan_pengaduan', function (Blueprint $table) {
            $table->index('status_laporan');
            $table->index('barang_id');
            $table->index('id_user');
            $table->index('created_at');
        });

        // ================================
        // INDEX UNTUK ACTIVITY LOGS
        // ================================
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('aksi');
            $table->index('created_at');
        });

        // ⚠ TIDAK TAMBAH INDEX barang.nup
        // karena sudah ada index sebelumnya
    }

    public function down(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {
            $table->dropIndex(['status_laporan']);
            $table->dropIndex(['barang_id']);
            $table->dropIndex(['id_user']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['aksi']);
            $table->dropIndex(['created_at']);
        });

        // Tidak perlu drop index barang.nup
    }
};