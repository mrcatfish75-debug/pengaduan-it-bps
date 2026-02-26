<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengaduan;

class DashboardController extends Controller
{
    public function index()
    {
        $total = LaporanPengaduan::count();

        $menungguAdmin = LaporanPengaduan::where('status_laporan', 'MENUNGGU_REVIEW_ADMIN')->count();

        $menungguKasubag = LaporanPengaduan::where('status_laporan', 'MENUNGGU_KEPUTUSAN_KASUBAG')->count();

        $selesai = LaporanPengaduan::where('status_laporan', 'SELESAI')->count();

        $ditolak = LaporanPengaduan::where('status_laporan', 'DITOLAK')->count();

        return view('admin.dashboard', compact(
            'total',
            'menungguAdmin',
            'menungguKasubag',
            'selesai',
            'ditolak'
        ));
    }
}