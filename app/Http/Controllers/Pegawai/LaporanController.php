<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LaporanPengaduan;
use App\Models\Barang;
use App\Models\ActivityLog;
use App\Http\Requests\StoreLaporanRequest;

class LaporanController extends Controller
{

    public function dashboard()
    {

        // ================= STATISTIK =================

        $total = LaporanPengaduan::count();

        $proses = LaporanPengaduan::whereIn(
            'status_laporan',
            [
                'MENUNGGU_REVIEW_ADMIN',
                'MENUNGGU_KEPUTUSAN_KASUBAG',
                'DIKIRIM_VENDOR',
                'MENUNGGU_PENGADAAN'
            ]
        )->count();

        $selesai = LaporanPengaduan::where(
            'status_laporan',
            'SELESAI'
        )->count();

        $ditolak = LaporanPengaduan::where(
            'status_laporan',
            'DITOLAK'
        )->count();


        // ================= LAPORAN TERBARU =================

        $laporan = LaporanPengaduan::with(['user','barang'])
            ->latest()
            ->take(10)
            ->get();


        return view('pegawai.dashboard', compact(
            'total',
            'proses',
            'selesai',
            'ditolak',
            'laporan'
        ));
    }



    public function create()
    {

        $barang = Barang::whereNotIn('kondisi', [
                        'Dalam Perbaikan (Internal)',
                        'Service Eksternal'
                    ])
                    ->orderBy('nup')
                    ->get();

        return view('pegawai.create_laporan', compact('barang'));
    }



    public function store(StoreLaporanRequest $request)
    {

        DB::transaction(function () use ($request) {

            $barang = Barang::where('id', $request->barang_id)
                ->lockForUpdate()
                ->firstOrFail();


            $isDiproses = LaporanPengaduan::where('barang_id', $barang->id)
                ->whereIn('status_laporan', [
                    'MENUNGGU_REVIEW_ADMIN',
                    'MENUNGGU_KEPUTUSAN_KASUBAG'
                ])
                ->exists();

            if ($isDiproses) {
                abort(409, 'Barang ini masih dalam proses laporan.');
            }


            $allowedPrioritas = ['RENDAH','SEDANG','TINGGI'];

            if (!in_array($request->prioritas, $allowedPrioritas)) {
                abort(403, 'Prioritas tidak valid.');
            }


            $laporan = LaporanPengaduan::create([
                'id_user'           => Auth::id(),
                'barang_id'         => $barang->id,
                'jenis_kerusakan'   => trim($request->jenis_kerusakan),
                'deskripsi_keluhan' => trim($request->deskripsi_keluhan),
                'prioritas'         => $request->prioritas,
                'tanggal_lapor'     => now(),
                'status_laporan'    => 'MENUNGGU_REVIEW_ADMIN'
            ]);


            ActivityLog::create([
                'user_id'    => Auth::id(),
                'aksi'       => 'BUAT_LAPORAN',
                'model'      => 'LaporanPengaduan',
                'model_id'   => $laporan->id_laporan,
                'deskripsi'  => 'Pegawai membuat laporan baru',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

        });


        return redirect()
            ->route('pegawai.laporan_saya')
            ->with('success', 'Laporan berhasil dikirim.');
    }



    public function myReports()
    {

        $laporan = LaporanPengaduan::with('barang')
            ->where('id_user', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);


        ActivityLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'LIHAT_LAPORAN_SAYA',
            'model'      => 'LaporanPengaduan',
            'model_id'   => null,
            'deskripsi'  => 'Pegawai membuka halaman laporan saya',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);


        return view('pegawai.laporan_saya', compact('laporan'));
    }

}