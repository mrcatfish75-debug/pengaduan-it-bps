<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengaduan;
use App\Models\ActivityLog;
use App\Http\Requests\VerifikasiAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * ==============================================
     * LIST SEMUA LAPORAN (Filter + Search + Paging)
     * ==============================================
     */
    public function index(Request $request)
    {
        $query = LaporanPengaduan::with(['user','barang'])
                    ->orderBy('created_at','desc');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_laporan', $request->status);
        }

        // Search berdasarkan NUP barang
        if ($request->filled('search')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nup', 'like', '%' . $request->search . '%');
            });
        }

        $laporan = $query->paginate(10)->withQueryString();

        return view('admin.laporan.index', compact('laporan'));
    }

    /**
     * ==============================================
     * VERIFIKASI ADMIN (Enterprise Safe)
     * ==============================================
     */
    public function verifikasi(VerifikasiAdminRequest $request, $id)
    {
        $laporan = LaporanPengaduan::findOrFail($id);

        // 🔒 Authorization via Policy
        $this->authorize('update', $laporan);

        // 🔒 Cegah verifikasi ulang
        if ($laporan->status_laporan !== 'MENUNGGU_REVIEW_ADMIN') {
            return back()->with('error','Laporan tidak bisa diverifikasi karena status sudah berubah.');
        }

        DB::transaction(function () use ($request, $laporan) {

            $laporan->status_admin = $request->status_admin;
            $laporan->keputusan_admin = $request->keputusan_admin;
            $laporan->tanggal_verifikasi_admin = now();

            // 🔐 State Transition Lock
            if ($request->status_admin === 'DITOLAK_ADMIN') {
                $laporan->status_laporan = 'DITOLAK';
            } else {
                $laporan->status_laporan = 'MENUNGGU_KEPUTUSAN_KASUBAG';
            }

            $laporan->save();

            // 📝 Audit Log (IP + User Agent)
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aksi'      => 'VERIFIKASI_ADMIN',
                'model'     => 'LaporanPengaduan',
                'model_id'  => $laporan->id_laporan,
                'deskripsi' => 'Admin memberikan rekomendasi: ' . $request->status_admin,
                'ip_address'=> request()->ip(),
                'user_agent'=> request()->userAgent(),
            ]);
        });

        return back()->with('success','Verifikasi admin berhasil.');
    }

    /**
     * ==============================================
     * DETAIL LAPORAN
     * ==============================================
     */
    public function show($id)
    {
        $laporan = LaporanPengaduan::with(['user','barang'])
            ->findOrFail($id);

        // 🔒 Policy
        $this->authorize('view', $laporan);

        // 📝 Log akses detail (monitoring internal abuse)
        ActivityLog::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'LIHAT_DETAIL_LAPORAN_ADMIN',
            'model'     => 'LaporanPengaduan',
            'model_id'  => $laporan->id_laporan,
            'deskripsi' => 'Admin membuka detail laporan',
            'ip_address'=> request()->ip(),
            'user_agent'=> request()->userAgent(),
        ]);

        return view('admin.laporan.show', compact('laporan'));
    }
}