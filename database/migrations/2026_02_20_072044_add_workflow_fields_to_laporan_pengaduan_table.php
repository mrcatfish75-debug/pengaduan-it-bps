<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {

            $table->string('status_laporan')->default('MENUNGGU_VERIFIKASI_ADMIN')->change();

            $table->string('status_admin')->nullable();
            $table->string('status_kasubag')->nullable();

            $table->text('keputusan_admin')->nullable();
            $table->text('keputusan_kasubag')->nullable();

            $table->timestamp('tanggal_verifikasi_admin')->nullable();
            $table->timestamp('tanggal_keputusan_kasubag')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {
            $table->dropColumn([
                'status_admin',
                'status_kasubag',
                'keputusan_admin',
                'keputusan_kasubag',
                'tanggal_verifikasi_admin',
                'tanggal_keputusan_kasubag',
            ]);
        });
    }
};