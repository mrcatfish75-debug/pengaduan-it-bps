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

    public function index(Request $request)
    {

        $search = trim(substr($request->input('search',''),0,100));

        $query = LaporanPengaduan::with(['user','barang'])
                    ->orderByDesc('created_at');

        if ($request->filled('status')) {

            $allowed = [
                'MENUNGGU_REVIEW_ADMIN',
                'MENUNGGU_KEPUTUSAN_KASUBAG',
                'DIKIRIM_VENDOR',
                'MENUNGGU_PENGADAAN',
                'SELESAI',
                'DITOLAK'
            ];

            if (in_array($request->status,$allowed)) {
                $query->where('status_laporan',$request->status);
            }
        }

        if ($search) {

            $query->where(function ($q) use ($search) {

                $q->whereHas('barang', function ($qb) use ($search) {
                    $qb->where('nup','like',"%{$search}%")
                       ->orWhere('nama_barang','like',"%{$search}%");
                })

                ->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('name','like',"%{$search}%")
                       ->orWhere('email','like',"%{$search}%");
                });

            });
        }

        $laporan = $query->paginate(10)->withQueryString();

        return view('admin.laporan.index',compact('laporan'));
    }



    public function show($id)
    {
        abort_if(!is_numeric($id),404);

        $laporan = LaporanPengaduan::with(['user','barang'])
            ->where('id_laporan',$id)
            ->firstOrFail();

        return view('admin.laporan.show',compact('laporan'));
    }



    public function verifikasi(VerifikasiAdminRequest $request,$id)
    {

        abort_if(!is_numeric($id),404);

        $laporan = LaporanPengaduan::with('barang')
            ->where('id_laporan',$id)
            ->firstOrFail();

        if ($laporan->status_laporan !== 'MENUNGGU_REVIEW_ADMIN') {
            return redirect()->route('admin.laporan')
                ->with('error','Laporan sudah diproses.');
        }

        DB::transaction(function () use ($request,$laporan){

            $statusLaporan=null;
            $tanggalSelesai=null;

            switch ($request->status_admin){

                case 'REKOMENDASI_SERVIS_INTERNAL':

                    $statusLaporan='SELESAI';
                    $tanggalSelesai=now();

                    if($laporan->barang){
                        $laporan->barang->update([
                            'kondisi'=>'Dalam Perbaikan (Internal)'
                        ]);
                    }

                break;

                case 'REKOMENDASI_SERVIS_EKSTERNAL':

                    $statusLaporan='MENUNGGU_KEPUTUSAN_KASUBAG';

                    if($laporan->barang){
                        $laporan->barang->update([
                            'kondisi'=>'Service Eksternal'
                        ]);
                    }

                break;

                case 'REKOMENDASI_GANTI_BARU':

                    $statusLaporan='MENUNGGU_KEPUTUSAN_KASUBAG';

                break;

                case 'DITOLAK_ADMIN':

                    $statusLaporan='DITOLAK';

                break;

                default:
                    abort(403,'Status tidak valid');
            }

            $laporan->update([
                'status_admin'=>$request->status_admin,
                'keputusan_admin'=>trim($request->keputusan_admin),
                'tanggal_verifikasi_admin'=>now(),
                'status_laporan'=>$statusLaporan,
                'tanggal_selesai'=>$tanggalSelesai
            ]);

            ActivityLog::create([
                'user_id'=>Auth::id(),
                'aksi'=>'VERIFIKASI_ADMIN',
                'model'=>'LaporanPengaduan',
                'model_id'=>$laporan->id_laporan,
                'deskripsi'=>'Admin memberikan keputusan: '.$request->status_admin,
                'ip_address'=>request()->ip(),
                'user_agent'=>request()->userAgent(),
            ]);

        });

        return redirect()->route('admin.laporan')
            ->with('success','Laporan berhasil diproses.');
    }



    public function selesai($id)
    {

        abort_if(!is_numeric($id),404);

        $laporan=LaporanPengaduan::with('barang')
            ->where('id_laporan',$id)
            ->firstOrFail();

        if(!in_array($laporan->status_laporan,['DIKIRIM_VENDOR','MENUNGGU_PENGADAAN'])){
            return back()->with('error','Status laporan tidak valid.');
        }

        DB::transaction(function() use ($laporan){

            $laporan->update([
                'status_laporan'=>'SELESAI',
                'tanggal_selesai'=>now()
            ]);

            if($laporan->barang){
                $laporan->barang->update([
                    'kondisi'=>'Normal'
                ]);
            }

            ActivityLog::create([
                'user_id'=>Auth::id(),
                'aksi'=>'SELESAIKAN_LAPORAN',
                'model'=>'LaporanPengaduan',
                'model_id'=>$laporan->id_laporan,
                'deskripsi'=>'Admin menandai laporan selesai',
                'ip_address'=>request()->ip(),
                'user_agent'=>request()->userAgent(),
            ]);

        });

        return back()->with('success','Laporan berhasil diselesaikan.');
    }



    public function edit($id)
    {
        abort_if(!is_numeric($id),404);

        $laporan=LaporanPengaduan::where('id_laporan',$id)->firstOrFail();

        return view('admin.laporan.edit',compact('laporan'));
    }



    public function update(Request $request,$id)
    {

        abort_if(!is_numeric($id),404);

        $request->validate([
            'prioritas'=>'required|in:RENDAH,SEDANG,TINGGI',
            'deskripsi_keluhan'=>'required|string|max:2000'
        ]);

        $laporan=LaporanPengaduan::where('id_laporan',$id)->firstOrFail();

        $laporan->update([
            'prioritas'=>$request->prioritas,
            'deskripsi_keluhan'=>trim($request->deskripsi_keluhan)
        ]);

        ActivityLog::create([
            'user_id'=>Auth::id(),
            'aksi'=>'EDIT_LAPORAN_ADMIN',
            'model'=>'LaporanPengaduan',
            'model_id'=>$laporan->id_laporan,
            'deskripsi'=>'Admin mengedit laporan',
            'ip_address'=>request()->ip(),
            'user_agent'=>request()->userAgent(),
        ]);

        return redirect()->route('admin.laporan')
            ->with('success','Laporan berhasil diperbarui.');
    }



    public function destroy($id)
    {

        abort_if(!is_numeric($id),404);

        $laporan=LaporanPengaduan::where('id_laporan',$id)->firstOrFail();

        $laporan->delete();

        ActivityLog::create([
            'user_id'=>Auth::id(),
            'aksi'=>'DELETE_LAPORAN_ADMIN',
            'model'=>'LaporanPengaduan',
            'model_id'=>$id,
            'deskripsi'=>'Admin menghapus laporan',
            'ip_address'=>request()->ip(),
            'user_agent'=>request()->userAgent(),
        ]);

        return redirect()->route('admin.laporan')
            ->with('success','Laporan berhasil dihapus.');
    }
}