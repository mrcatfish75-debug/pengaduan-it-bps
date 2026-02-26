<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLaporanRequest extends FormRequest
{
    /**
     * =====================================================
     * AUTHORIZATION
     * =====================================================
     * Hanya Pegawai boleh membuat laporan
     */
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'pegawai';
    }

    /**
     * =====================================================
     * VALIDATION RULES
     * =====================================================
     */
    public function rules(): array
    {
        return [
            'barang_id' => [
                'required',
                'integer',
                'exists:barang,id'
            ],

            'jenis_kerusakan' => [
                'required',
                'string',
                'max:255'
            ],

            'deskripsi_keluhan' => [
                'required',
                'string',
                'max:2000'
            ],

            'prioritas' => [
                'required',
                Rule::in(['Low','Normal','High'])
            ],
        ];
    }

    /**
     * =====================================================
     * CUSTOM ERROR MESSAGE
     * =====================================================
     */
    public function messages(): array
    {
        return [
            'barang_id.required' => 'Barang wajib dipilih.',
            'barang_id.exists'   => 'Barang tidak ditemukan.',

            'jenis_kerusakan.required' =>
                'Jenis kerusakan wajib diisi.',

            'deskripsi_keluhan.required' =>
                'Deskripsi keluhan wajib diisi.',

            'prioritas.required' =>
                'Prioritas wajib dipilih.',

            'prioritas.in' =>
                'Prioritas tidak valid.',
        ];
    }

    /**
     * =====================================================
     * SANITIZE INPUT (ANTI FRONTEND MANIPULATION)
     * =====================================================
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'jenis_kerusakan'   => trim($this->jenis_kerusakan),
            'deskripsi_keluhan' => trim($this->deskripsi_keluhan),
        ]);
    }
}