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
    /*
    |--------------------------------------------------------------------------
    | FORM LAPORAN
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | SIMPAN LAPORAN
    |--------------------------------------------------------------------------
    */
    public function store(StoreLaporanRequest $request)
    {
        DB::transaction(function () use ($request) {

            /*
            |====================================================
            | 🔒 DATABASE ROW LOCK (ANTI DOUBLE SUBMIT)
            |====================================================
            | Mencegah:
            | - double click
            | - spam submit
            | - network retry
            | - multi tab submit
            */

            $barang = Barang::where('id', $request->barang_id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |====================================================
            | 🔒 CEK ULANG SETELAH LOCK
            |====================================================
            */

            $isDiproses = LaporanPengaduan::where('barang_id', $barang->id)
                ->whereIn('status_laporan', [
                    'MENUNGGU_REVIEW_ADMIN',
                    'MENUNGGU_KEPUTUSAN_KASUBAG'
                ])
                ->exists();

            if ($isDiproses) {
                abort(409, 'Barang ini masih dalam proses laporan.');
            }

            /*
            |====================================================
            | CREATE LAPORAN
            |====================================================
            */

            $laporan = LaporanPengaduan::create([
                'id_user'            => Auth::id(),
                'barang_id'          => $barang->id,
                'jenis_kerusakan'    => $request->jenis_kerusakan,
                'deskripsi_keluhan'  => $request->deskripsi_keluhan,
                'prioritas'          => $request->prioritas,
                'tanggal_lapor'      => now(),
                'status_laporan'     => 'MENUNGGU_REVIEW_ADMIN'
            ]);

            /*
            |====================================================
            | 📝 AUDIT LOG
            |====================================================
            */

            ActivityLog::record(
                'BUAT_LAPORAN',
                'LaporanPengaduan',
                $laporan->id_laporan,
                'Pegawai membuat laporan baru'
            );
        });

        return redirect()
            ->route('pegawai.dashboard')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    /*
    |--------------------------------------------------------------------------
    | LAPORAN SAYA (DATA ISOLATION)
    |--------------------------------------------------------------------------
    */
    public function myReports()
    {
        $laporan = LaporanPengaduan::with('barang')
            ->where('id_user', Auth::id())
            ->orderBy('created_at','desc')
            ->paginate(10);

        ActivityLog::record(
            'LIHAT_LAPORAN_SAYA',
            'LaporanPengaduan',
            null,
            'Pegawai membuka halaman laporan saya'
        );

        return view('pegawai.laporan_saya', compact('laporan'));
    }
}