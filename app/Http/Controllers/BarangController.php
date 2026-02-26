<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Import data barang dari Excel
     */
    public function import(Request $request)
    {
        // 🔒 Pastikan hanya admin yang boleh import
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // 🔒 Validasi file lebih ketat
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048'
        ]);

        try {

            // Simpan sementara di storage (bukan public)
            $filePath = $request->file('file')->store('imports');

            // Import data
            Excel::import(new BarangImport, storage_path('app/' . $filePath));

            // 📝 Audit Log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'IMPORT_BARANG',
                'model' => 'Barang',
                'model_id' => null,
                'deskripsi' => 'Import data barang melalui Excel'
            ]);

            // Hapus file setelah import (tidak disimpan lama)
            Storage::delete($filePath);

            return redirect()->back()->with('success', 'Data barang berhasil diimport!');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Terjadi kesalahan saat import.');
        }
    }
}