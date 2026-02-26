<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusBarang extends Model
{
    protected $table = 'status_barang';
    protected $primaryKey = 'id_status';

    protected $fillable = [
        'id_laporan',
        'kondisi_barang_akhir',
        'tanggal_update'
    ];

    // relasi ke laporan
    public function laporan()
    {
        return $this->belongsTo(LaporanPengaduan::class,'id_laporan');
    }
}
