<?php

namespace App\Support;

class LaporanStatus
{
    public const FLOW = [

        'MENUNGGU_REVIEW_ADMIN' => [
            'MENUNGGU_KEPUTUSAN_KASUBAG'
        ],

        'MENUNGGU_KEPUTUSAN_KASUBAG' => [
            'SELESAI',
            'DITOLAK'
        ],

        'SELESAI' => [],
        'DITOLAK' => [],
    ];

    public static function canTransition($from, $to): bool
    {
        return in_array($to, self::FLOW[$from] ?? []);
    }
}