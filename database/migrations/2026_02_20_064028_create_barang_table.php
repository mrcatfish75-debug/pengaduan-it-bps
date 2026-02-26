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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();

            // Identitas Barang
            $table->string('kode_barang')->nullable();
            $table->string('nup')->unique(); // NUP wajib unik
            $table->string('nama_barang');

            // Detail Tambahan
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->string('kondisi')->nullable(); 
            $table->string('lokasi_ruang')->nullable();

            // Informasi Perolehan
            $table->date('tanggal_perolehan')->nullable();
            $table->bigInteger('nilai_perolehan')->nullable();

            $table->timestamps();

            // Index tambahan supaya pencarian cepat
            $table->index('nup');
            $table->index('kode_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};