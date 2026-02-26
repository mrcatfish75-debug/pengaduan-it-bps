<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/data_BMN.xlsx');

        if (!File::exists($filePath)) {
            Log::warning('BarangSeeder: File data_barang.xlsx tidak ditemukan di storage/app.');
            return;
        }

        try {
            Excel::import(new BarangImport, $filePath);

            Log::info('BarangSeeder: Import data barang berhasil.');
        } catch (\Throwable $e) {
            Log::error('BarangSeeder: Gagal import data barang.', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
        }
    }
}