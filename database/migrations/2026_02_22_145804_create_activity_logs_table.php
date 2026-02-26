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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->string('aksi'); 
        // contoh: VERIFIKASI_ADMIN, PUTUSAN_KASUBAG

        $table->string('model'); 
        // contoh: LaporanPengaduan

        $table->unsignedBigInteger('model_id'); 
        // id data yang diubah

        $table->text('deskripsi')->nullable();

        $table->timestamps();
    });
}
};
