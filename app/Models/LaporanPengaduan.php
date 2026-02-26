<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\LaporanStatus;

class LaporanPengaduan extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE CONFIG
    |--------------------------------------------------------------------------
    */
    protected $table = 'laporan_pengaduan';
    protected $primaryKey = 'id_laporan';

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'id_user',
        'barang_id',
        'jenis_kerusakan',
        'deskripsi_keluhan',
        'prioritas',
        'tanggal_lapor',
        'tanggal_selesai',

        // workflow
        'status_laporan',
        'status_admin',
        'status_kasubag',
        'keputusan_admin',
        'keputusan_kasubag',
        'tanggal_verifikasi_admin',
        'tanggal_keputusan_kasubag',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // User pelapor
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Barang terkait
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Status barang hasil akhir
    public function statusBarang()
    {
        return $this->hasOne(StatusBarang::class, 'id_laporan');
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah barang masih diproses laporan lain
     */
    public static function masihDiproses($barangId): bool
    {
        return self::where('barang_id', $barangId)
            ->whereIn('status_laporan', [
                'MENUNGGU_REVIEW_ADMIN',
                'MENUNGGU_KEPUTUSAN_KASUBAG'
            ])
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | 🔒 WORKFLOW GUARD (FINAL SECURITY LAYER)
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::updating(function ($laporan) {

            /*
            --------------------------------------------------
            FINAL STATE LOCK
            laporan selesai / ditolak tidak boleh diubah
            --------------------------------------------------
            */
            if (in_array(
                $laporan->getOriginal('status_laporan'),
                ['SELESAI', 'DITOLAK']
            )) {
                throw new \Exception(
                    'Final laporan tidak boleh diubah.'
                );
            }

            /*
            --------------------------------------------------
            STATUS TRANSITION VALIDATION
            --------------------------------------------------
            */
            if ($laporan->isDirty('status_laporan')) {

                $old = $laporan->getOriginal('status_laporan');
                $new = $laporan->status_laporan;

                if (!LaporanStatus::canTransition($old, $new)) {
                    throw new \Exception(
                        "Illegal status transition: {$old} → {$new}"
                    );
                }
            }
        });
    }
}