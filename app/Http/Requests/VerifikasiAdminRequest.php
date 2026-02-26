<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'status_admin' => 'required|in:REKOMENDASI_SERVIS_INTERNAL,REKOMENDASI_SERVIS_EKSTERNAL,REKOMENDASI_GANTI_BARU,DITOLAK_ADMIN',
            'keputusan_admin' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'status_admin.required' => 'Status admin wajib dipilih.',
            'status_admin.in' => 'Status admin tidak valid.',
        ];
    }
}