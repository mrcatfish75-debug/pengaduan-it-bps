<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengaduan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST LAPORAN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $laporan = LaporanPengaduan::with(['user','barang'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL LAPORAN
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $laporan = LaporanPengaduan::with(['user','barang'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI ADMIN (API)
    |--------------------------------------------------------------------------
    */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_admin' => 'required',
            'keputusan_admin' => 'nullable|string'
        ]);

        $laporan = LaporanPengaduan::findOrFail($id);

        DB::transaction(function () use ($request, $laporan) {

            $laporan->status_admin = $request->status_admin;
            $laporan->keputusan_admin = $request->keputusan_admin;
            $laporan->tanggal_verifikasi_admin = now();

            $laporan->status_laporan =
                $request->status_admin === 'DITOLAK_ADMIN'
                ? 'DITOLAK'
                : 'MENUNGGU_KEPUTUSAN_KASUBAG';

            $laporan->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'VERIFIKASI_ADMIN_API',
                'model' => 'LaporanPengaduan',
                'model_id' => $laporan->id_laporan,
                'deskripsi' => 'Admin verifikasi via API',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil'
        ]);
    }
}