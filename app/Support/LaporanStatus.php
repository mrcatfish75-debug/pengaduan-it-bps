<?php

namespace App\Support;

class LaporanStatus
{

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW STATE MACHINE
    |--------------------------------------------------------------------------
    | Mendefinisikan semua transisi status yang valid
    */

    public const FLOW = [

        /*
        |--------------------------------------------------------------------------
        | ADMIN REVIEW
        |--------------------------------------------------------------------------
        | Admin dapat:
        | - kirim ke kasubag
        | - selesaikan langsung (servis internal)
        | - tolak laporan
        */

        'MENUNGGU_REVIEW_ADMIN' => [
            'MENUNGGU_KEPUTUSAN_KASUBAG',
            'SELESAI',
            'DITOLAK'
        ],


        /*
        |--------------------------------------------------------------------------
        | KASUBAG DECISION
        |--------------------------------------------------------------------------
        */

        'MENUNGGU_KEPUTUSAN_KASUBAG' => [
            'DIKIRIM_VENDOR',
            'MENUNGGU_PENGADAAN',
            'SELESAI',
            'DITOLAK'
        ],


        /*
        |--------------------------------------------------------------------------
        | VENDOR PROCESS
        |--------------------------------------------------------------------------
        */

        'DIKIRIM_VENDOR' => [
            'SELESAI'
        ],


        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT PROCESS
        |--------------------------------------------------------------------------
        */

        'MENUNGGU_PENGADAAN' => [
            'SELESAI'
        ],


        /*
        |--------------------------------------------------------------------------
        | FINAL STATE
        |--------------------------------------------------------------------------
        */

        'SELESAI' => [],
        'DITOLAK' => [],
    ];


    /*
    |--------------------------------------------------------------------------
    | VALIDASI TRANSISI
    |--------------------------------------------------------------------------
    */

    public static function canTransition(string $from, string $to): bool
    {
        return in_array($to, self::FLOW[$from] ?? []);
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER UNTUK DEBUG
    |--------------------------------------------------------------------------
    */

    public static function getAllowedTransitions(string $status): array
    {
        return self::FLOW[$status] ?? [];
    }
}