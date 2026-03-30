<?php

namespace App\Http\Controllers\Kasubag;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengaduan;
use App\Models\ActivityLog;
use App\Models\Barang;
use App\Http\Requests\PutusanKasubagRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LaporanController extends Controller
{

    public function index(Request $request)
    {
        $laporan = LaporanPengaduan::with(['user','barang'])
            ->latest()
            ->take(5)
            ->get();

        $menunggu = LaporanPengaduan::where('status_laporan','MENUNGGU_KEPUTUSAN_KASUBAG')->count();
        $selesai = LaporanPengaduan::where('status_laporan','SELESAI')->count();
        $ditolak = LaporanPengaduan::where('status_laporan','DITOLAK')->count();

        return view('kasubag.dashboard', compact(
            'laporan',
            'menunggu',
            'selesai',
            'ditolak'
        ));
    }



    public function show($id)
    {
        abort_if(!is_numeric($id),404);

        $laporan = LaporanPengaduan::with(['user','barang'])
            ->where('id_laporan',$id)
            ->firstOrFail();

        return view('kasubag.show', compact('laporan'));
    }



    public function putusan(PutusanKasubagRequest $request, $id)
    {

        abort_if(!is_numeric($id),404);

        $laporan = LaporanPengaduan::with('barang')
            ->where('id_laporan',$id)
            ->firstOrFail();

        if ($laporan->status_laporan !== 'MENUNGGU_KEPUTUSAN_KASUBAG') {
            return back()->with('error','Laporan sudah memiliki keputusan.');
        }

        DB::transaction(function () use ($request,$laporan){

            $laporan->update([
                'status_kasubag' => $request->status_kasubag,
                'keputusan_kasubag' => trim($request->keputusan_kasubag),
                'tanggal_keputusan_kasubag' => now(),
            ]);


            switch ($request->status_kasubag) {

                case 'DISETUJUI_SERVIS_INTERNAL':

                    $laporan->update([
                        'status_laporan' => 'SELESAI',
                        'tanggal_selesai' => now()
                    ]);

                    if ($laporan->barang) {
                        $laporan->barang->update([
                            'kondisi' => 'Dalam Perbaikan (Internal)'
                        ]);
                    }

                break;


                case 'DISETUJUI_SERVIS_EKSTERNAL':

                    $laporan->update([
                        'status_laporan' => 'DIKIRIM_VENDOR'
                    ]);

                    if ($laporan->barang) {
                        $laporan->barang->update([
                            'kondisi' => 'Dikirim ke Vendor'
                        ]);
                    }

                break;


                case 'DISETUJUI_GANTI_BARU':

                    $laporan->update([
                        'status_laporan' => 'MENUNGGU_PENGADAAN'
                    ]);

                    if ($laporan->barang) {
                        $laporan->barang->update([
                            'kondisi' => 'Menunggu Pengadaan'
                        ]);
                    }

                break;


                case 'DITOLAK_KASUBAG':

                    $laporan->update([
                        'status_laporan' => 'DITOLAK'
                    ]);

                break;

                default:
                    abort(403,'Status keputusan tidak valid');
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'PUTUSAN_KASUBAG',
                'model' => 'LaporanPengaduan',
                'model_id' => $laporan->id_laporan,
                'deskripsi' => 'Kasubag memutuskan: '.$request->status_kasubag,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

        });

        return redirect()
            ->route('kasubag.dashboard')
            ->with('success','Keputusan berhasil disimpan.');
    }



    public function hasil(Request $request)
    {

        $search = trim(substr($request->input('search',''),0,100));

        $query = LaporanPengaduan::with(['user','barang']);

        if ($request->filled('status')) {

            $allowedStatus = [
                'SELESAI',
                'DITOLAK',
                'DIKIRIM_VENDOR',
                'MENUNGGU_PENGADAAN'
            ];

            if (in_array($request->status,$allowedStatus)) {
                $query->where('status_laporan',$request->status);
            }
        }

        if ($search) {

            $query->whereHas('barang', function($q) use ($search){
                $q->where('nup','like','%'.$search.'%');
            });

        }

        $laporan = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('kasubag.hasil', compact('laporan'));
    }



    public function barang()
    {

        $sort = request('sort','created_at');
        $direction = request('direction','desc');

        $allowedSort = ['nup','nama_barang','kondisi','lokasi_ruang','created_at'];

        if (!in_array($sort,$allowedSort)) {
            $sort = 'created_at';
        }

        $barang = Barang::orderBy($sort,$direction)
            ->paginate(10)
            ->withQueryString();

        return view('kasubag.barang', compact('barang','sort','direction'));
    }



    public function updateBarang(Request $request, $id)
    {

        abort_if(!is_numeric($id),404);

        $allowedKondisi = [
            'Normal',
            'Dalam Perbaikan (Internal)',
            'Service Eksternal',
            'Dikirim ke Vendor',
            'Menunggu Pengadaan'
        ];

        $request->validate([
            'kondisi' => 'required|string'
        ]);

        if (!in_array($request->kondisi,$allowedKondisi)) {
            abort(403,'Kondisi tidak valid');
        }

        $barang = Barang::findOrFail($id);

        $barang->update([
            'kondisi' => $request->kondisi
        ]);

        return back()->with('success','Kondisi berhasil diperbarui.');
    }



    public function destroyBarang($id)
    {

        abort_if(!is_numeric($id),404);

        $barang = Barang::findOrFail($id);

        $barang->delete();

        return back()->with('success','Barang berhasil dihapus.');
    }

}