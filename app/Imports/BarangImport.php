<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class BarangImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | NORMALISASI DATA
            |--------------------------------------------------------------------------
            */

            $statusBMN = strtolower(trim($row['status_bmn'] ?? ''));

            /*
            |--------------------------------------------------------------------------
            | HANYA IMPORT BMN AKTIF
            |--------------------------------------------------------------------------
            */

            if ($statusBMN !== 'aktif') {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI NUP
            |--------------------------------------------------------------------------
            */

            $nup = trim((string)($row['nup'] ?? ''));

            if (!$nup) {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | FORMAT TANGGAL
            |--------------------------------------------------------------------------
            */

            $tanggalPerolehan = null;

            if (!empty($row['tanggal_perolehan'])) {
                try {
                    $tanggalPerolehan =
                        Date::excelToDateTimeObject(
                            $row['tanggal_perolehan']
                        )->format('Y-m-d');
                } catch (\Throwable $e) {
                    $tanggalPerolehan = null;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPSERT BARANG
            |--------------------------------------------------------------------------
            */

            return Barang::updateOrCreate(
                [
                    'nup' => $nup
                ],
                [
                    'kode_barang'       => trim($row['kode_barang'] ?? ''),
                    'nama_barang'       => trim($row['nama_barang'] ?? ''),
                    'merk'              => trim($row['merk'] ?? ''),
                    'tipe'              => trim($row['tipe'] ?? ''),
                    'kondisi'           => trim($row['kondisi'] ?? ''),
                    'lokasi_ruang'      => trim($row['lokasi_ruang'] ?? ''),
                    'tanggal_perolehan' => $tanggalPerolehan,
                    'nilai_perolehan'   => $row['nilai_perolehan'] ?? 0,
                ]
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | LOG ERROR IMPORT (ANTI SILENT FAIL)
            |--------------------------------------------------------------------------
            */

            Log::error('BMN_IMPORT_ERROR', [
                'row' => $row,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }
}