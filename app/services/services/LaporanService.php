<?php

namespace App\Services;

use App\Models\LaporanPengaduan;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    public function verifikasiAdmin($laporan, $request)
    {
        return DB::transaction(function () use ($laporan, $request) {

            $laporan->status_admin = $request->status_admin;
            $laporan->keputusan_admin = $request->keputusan_admin;
            $laporan->tanggal_verifikasi_admin = now();

            $laporan->status_laporan =
                $request->status_admin === 'DITOLAK_ADMIN'
                ? 'DITOLAK'
                : 'MENUNGGU_KEPUTUSAN_KASUBAG';

            $laporan->save();

            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aksi'      => 'VERIFIKASI_ADMIN',
                'model'     => 'LaporanPengaduan',
                'model_id'  => $laporan->id_laporan,
                'deskripsi' => 'Admin verifikasi',
                'ip_address'=> request()->ip(),
                'user_agent'=> request()->userAgent(),
            ]);
        });
    }
}