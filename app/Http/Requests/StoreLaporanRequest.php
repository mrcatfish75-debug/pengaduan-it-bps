<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLaporanRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'pegawai';
    }


    public function rules(): array
    {
        return [

            'barang_id' => [
                'bail',
                'required',
                'integer',
                'exists:barang,id'
            ],

            'jenis_kerusakan' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:255'
            ],

            'deskripsi_keluhan' => [
                'bail',
                'required',
                'string',
                'min:5',
                'max:2000'
            ],

            'prioritas' => [
                'bail',
                'required',
                'string',
                Rule::in(['RENDAH','SEDANG','TINGGI'])
            ],
        ];
    }


    public function messages(): array
    {
        return [

            'barang_id.required' => 'Barang wajib dipilih.',
            'barang_id.exists'   => 'Barang tidak ditemukan.',

            'jenis_kerusakan.required' =>
                'Jenis kerusakan wajib diisi.',

            'jenis_kerusakan.min' =>
                'Jenis kerusakan minimal 3 karakter.',

            'deskripsi_keluhan.required' =>
                'Deskripsi keluhan wajib diisi.',

            'deskripsi_keluhan.min' =>
                'Deskripsi keluhan minimal 5 karakter.',

            'prioritas.required' =>
                'Prioritas wajib dipilih.',

            'prioritas.in' =>
                'Prioritas tidak valid.',
        ];
    }


    protected function prepareForValidation(): void
    {

        $this->merge([

            'jenis_kerusakan' =>
                $this->jenis_kerusakan
                    ? trim(preg_replace('/\s+/',' ',$this->jenis_kerusakan))
                    : null,

            'deskripsi_keluhan' =>
                $this->deskripsi_keluhan
                    ? trim(preg_replace('/\s+/',' ',$this->deskripsi_keluhan))
                    : null,

        ]);
    }
}