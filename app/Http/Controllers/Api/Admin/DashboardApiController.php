<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengaduan;

class DashboardApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'total' => LaporanPengaduan::count(),
            'menunggu_admin' =>
                LaporanPengaduan::where(
                    'status_laporan',
                    'MENUNGGU_REVIEW_ADMIN'
                )->count(),

            'menunggu_kasubag' =>
                LaporanPengaduan::where(
                    'status_laporan',
                    'MENUNGGU_KEPUTUSAN_KASUBAG'
                )->count(),

            'selesai' =>
                LaporanPengaduan::where(
                    'status_laporan',
                    'SELESAI'
                )->count(),

            'ditolak' =>
                LaporanPengaduan::where(
                    'status_laporan',
                    'DITOLAK'
                )->count(),
        ]);
    }
}