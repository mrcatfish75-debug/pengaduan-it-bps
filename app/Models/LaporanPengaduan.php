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
                'MENUNGGU_KEPUTUSAN_KASUBAG',
                'DIKIRIM_VENDOR',
                'MENUNGGU_PENGADAAN'
            ])
            ->exists();
    }


    /**
     * Cek apakah laporan sudah final
     */

    public function isFinal(): bool
    {
        return in_array($this->status_laporan, [
            'SELESAI',
            'DITOLAK'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | WORKFLOW GUARD
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::updating(function ($laporan) {

            $oldStatus = $laporan->getOriginal('status_laporan');

            /*
            |--------------------------------------------------------------------------
            | FINAL STATE LOCK
            | hanya blok perubahan STATUS jika sudah final
            |--------------------------------------------------------------------------
            */

            if (in_array($oldStatus, ['SELESAI','DITOLAK'])) {

                if ($laporan->isDirty('status_laporan')) {
                    throw new \Exception(
                        'Status laporan final tidak boleh diubah.'
                    );
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | STATUS TRANSITION VALIDATION
            |--------------------------------------------------------------------------
            */

            if ($laporan->isDirty('status_laporan')) {

                $newStatus = $laporan->status_laporan;

                if (!LaporanStatus::canTransition($oldStatus, $newStatus)) {

                    throw new \Exception(
                        "Illegal status transition: {$oldStatus} → {$newStatus}"
                    );

                }
            }

        });
    }
}