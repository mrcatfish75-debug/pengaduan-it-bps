<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PutusanKasubagRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'kasubag';
    }


    public function rules(): array
    {
        return [

            'status_kasubag' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    'DISETUJUI_SERVIS_INTERNAL',
                    'DISETUJUI_SERVIS_EKSTERNAL',
                    'DISETUJUI_GANTI_BARU',
                    'DITOLAK_KASUBAG'
                ])
            ],

            'keputusan_kasubag' => [
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

            'status_kasubag.required' =>
                'Keputusan wajib dipilih.',

            'status_kasubag.in' =>
                'Status keputusan tidak valid.',

            'keputusan_kasubag.min' =>
                'Catatan keputusan minimal 3 karakter.',

            'keputusan_kasubag.max' =>
                'Catatan keputusan maksimal 1000 karakter.',
        ];
    }


    protected function prepareForValidation(): void
    {

        if ($this->keputusan_kasubag) {

            $this->merge([

                'keputusan_kasubag' =>
                    trim(preg_replace('/\s+/',' ',$this->keputusan_kasubag))

            ]);

        }
    }
}