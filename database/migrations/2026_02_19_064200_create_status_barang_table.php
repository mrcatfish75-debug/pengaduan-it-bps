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
    Schema::create('status_barang', function (Blueprint $table) {
        $table->id('id_status');

        // FK ke laporan (WAJIB ADA)
        $table->unsignedBigInteger('id_laporan');
        $table->foreign('id_laporan')
              ->references('id_laporan')
              ->on('laporan_pengaduan')
              ->cascadeOnDelete();

        // hasil akhir perbaikan
        $table->string('kondisi_barang_akhir');
        $table->date('tanggal_update');

        $table->timestamps();
    });
}


};
