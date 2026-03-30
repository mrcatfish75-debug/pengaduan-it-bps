<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiAdminRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'admin';
    }


    public function rules(): array
    {
        return [

            'status_admin' => [
                'bail',
                'required',
                'string',
                'in:REKOMENDASI_SERVIS_INTERNAL,REKOMENDASI_SERVIS_EKSTERNAL,REKOMENDASI_GANTI_BARU,DITOLAK_ADMIN'
            ],

            'keputusan_admin' => [
                'nullable',
                'string',
                'min:3',
                'max:1000'
            ],

        ];
    }


    public function messages(): array
    {
        return [

            'status_admin.required' =>
                'Keputusan admin wajib dipilih.',

            'status_admin.in' =>
                'Keputusan admin tidak valid.',

            'keputusan_admin.min' =>
                'Catatan admin minimal 3 karakter.',

            'keputusan_admin.max' =>
                'Catatan admin maksimal 1000 karakter.',

        ];
    }


    protected function prepareForValidation(): void
    {

        if ($this->keputusan_admin) {

            $this->merge([

                'keputusan_admin' =>
                    trim(preg_replace('/\s+/',' ',$this->keputusan_admin))

            ]);

        }
    }
}