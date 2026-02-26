<?php

namespace App\Http\Controllers\Kasubag;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengaduan;
use App\Models\ActivityLog;
use App\Http\Requests\PutusanKasubagRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * ======================================
     * DASHBOARD KASUBAG
     * ======================================
     */
    public function index()
    {
        $laporan = LaporanPengaduan::with(['user','barang'])
            ->where('status_laporan','MENUNGGU_KEPUTUSAN_KASUBAG')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('kasubag.dashboard', compact('laporan'));
    }

    /**
     * ======================================
     * PUTUSAN FINAL KASUBAG
     * ======================================
     */
    public function putusan(PutusanKasubagRequest $request, $id)
    {
        // ✅ FIX PRIMARY KEY
        $laporan = LaporanPengaduan::with('barang')
            ->where('id_laporan',$id)
            ->firstOrFail();

        // Prevent double decision
        if ($laporan->status_laporan !== 'MENUNGGU_KEPUTUSAN_KASUBAG') {
            return back()->with('error',
                'Laporan sudah memiliki keputusan.');
        }

        DB::transaction(function () use ($request,$laporan){

            $laporan->update([
                'status_kasubag' => $request->status_kasubag,
                'keputusan_kasubag' => $request->keputusan_kasubag,
                'tanggal_keputusan_kasubag' => now(),
            ]);

            /*
            =====================================
            FINAL STATUS FLOW
            =====================================
            */
            if ($request->status_kasubag === 'DITOLAK_KASUBAG') {

                $laporan->update([
                    'status_laporan' => 'DITOLAK'
                ]);

            } else {

                $laporan->update([
                    'status_laporan' => 'SELESAI'
                ]);

                if ($laporan->barang) {

                    match ($request->status_kasubag) {

                        'DISETUJUI_SERVIS_INTERNAL'
                            => $laporan->barang->update([
                                'kondisi' => 'Dalam Perbaikan (Internal)'
                            ]),

                        'DISETUJUI_SERVIS_EKSTERNAL'
                            => $laporan->barang->update([
                                'kondisi' => 'Service Eksternal'
                            ]),

                        'DISETUJUI_GANTI_BARU'
                            => $laporan->barang->update([
                                'kondisi' => 'Diganti Baru'
                            ]),
                    };
                }
            }

            /*
            =====================================
            AUDIT LOG
            =====================================
            */
            ActivityLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'PUTUSAN_KASUBAG',
                'model' => 'LaporanPengaduan',
                'model_id' => $laporan->id_laporan,
                'deskripsi' =>
                    'Kasubag memutuskan: '.$request->status_kasubag,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return redirect()
            ->route('kasubag.dashboard')
            ->with('success','✅ Keputusan berhasil disimpan.');
    }
}