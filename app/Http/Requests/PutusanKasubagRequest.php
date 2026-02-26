<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PutusanKasubagRequest extends FormRequest
{
    /**
     * =====================================================
     * AUTHORIZATION
     * =====================================================
     * Hanya Kasubag boleh memberi keputusan
     */
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'kasubag';
    }

    /**
     * =====================================================
     * VALIDATION RULES
     * =====================================================
     */
    public function rules(): array
    {
        return [
            'status_kasubag' => [
                'required',
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
                'max:1000'
            ],
        ];
    }

    /**
     * =====================================================
     * CUSTOM MESSAGE
     * =====================================================
     */
    public function messages(): array
    {
        return [
            'status_kasubag.required' =>
                'Keputusan wajib dipilih.',

            'status_kasubag.in' =>
                'Status keputusan tidak valid.',

            'keputusan_kasubag.max' =>
                'Catatan keputusan maksimal 1000 karakter.',
        ];
    }

    /**
     * =====================================================
     * SANITIZE INPUT
     * =====================================================
     */
    protected function prepareForValidation(): void
    {
        if ($this->keputusan_kasubag) {
            $this->merge([
                'keputusan_kasubag' =>
                    trim($this->keputusan_kasubag)
            ]);
        }
    }
}