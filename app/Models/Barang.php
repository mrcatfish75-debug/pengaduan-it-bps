<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nup',
        'nama_barang',
        'merk',
        'tipe',
        'kondisi',
        'lokasi_ruang',
        'tanggal_perolehan',
        'nilai_perolehan'
    ];

    // Relasi ke laporan pengaduan
    public function laporan()
    {
        return $this->hasMany(LaporanPengaduan::class, 'id_barang');
    }
}