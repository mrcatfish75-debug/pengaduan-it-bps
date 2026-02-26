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
    Schema::create('laporan_pengaduan', function (Blueprint $table) {
        $table->id('id_laporan');
        $table->foreignId('id_user')->constrained('users');
        $table->string('jenis_kerusakan');
        $table->text('deskripsi_keluhan');
        $table->string('prioritas')->default('Normal');
        $table->date('tanggal_lapor');
        $table->date('tanggal_selesai')->nullable();
        $table->string('status_laporan')->default('MENUNGGU_REVIEW');
        $table->timestamps();
    });
}

};
