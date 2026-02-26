<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {

            $table->unsignedBigInteger('barang_id')
                  ->nullable()
                  ->after('id_user'); // ✅ sesuaikan dengan struktur kamu

            $table->foreign('barang_id')
                  ->references('id')
                  ->on('barang')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {

            $table->dropForeign(['barang_id']);
            $table->dropColumn('barang_id');

        });
    }
};
