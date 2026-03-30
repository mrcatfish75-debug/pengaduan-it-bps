<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\ActivityLog;

class LoginRequest extends FormRequest
{

    /**
     * Authorization
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'max:255'
            ],

            'password' => [
                'bail',
                'required',
                'string',
                'max:255'
            ],

        ];
    }

    /**
     * Attempt login
     */
    public function authenticate(): void
    {

        $this->ensureIsNotRateLimited();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {

            /*
            =====================================================
            ANTI BRUTE FORCE DELAY
            =====================================================
            */

            usleep(700000); // 0.7 detik


            /*
            =====================================================
            RATE LIMITER HIT (10 menit)
            =====================================================
            */

            RateLimiter::hit($this->throttleKey(), 600);


            /*
            =====================================================
            LOGIN GAGAL LOG
            =====================================================
            */

            ActivityLog::create([
                'user_id' => null,
                'aksi' => 'LOGIN_GAGAL',
                'model' => 'User',
                'model_id' => null,
                'deskripsi' => 'Percobaan login gagal untuk email: '
                    . Str::limit($this->email,120),
                'ip_address' => $this->ip(),
                'user_agent' => Str::limit($this->userAgent(),255),
            ]);


            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }


        /*
        =====================================================
        CLEAR LIMITER JIKA BERHASIL
        =====================================================
        */

        RateLimiter::clear($this->throttleKey());
    }


    /**
     * Rate Limit Check
     */
    public function ensureIsNotRateLimited(): void
    {

        $maxAttempts = 5;

        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());


        /*
        =====================================================
        LOCKOUT LOG
        =====================================================
        */

        ActivityLog::create([
            'user_id' => null,
            'aksi' => 'LOCKOUT',
            'model' => 'User',
            'model_id' => null,
            'deskripsi' => 'Terlalu banyak percobaan login.',
            'ip_address' => $this->ip(),
            'user_agent' => Str::limit($this->userAgent(),255),
        ]);


        throw ValidationException::withMessages([
            'email' => 'Terlalu banyak percobaan login. Coba lagi dalam '
                . ceil($seconds / 60) . ' menit.',
        ]);
    }


    /**
     * Throttle Key
     */
    public function throttleKey(): string
    {
        return Str::lower(
            Str::transliterate(trim($this->input('email')))
        ) . '|' . $this->ip();
    }


    /**
     * Sanitize Input
     */
    protected function prepareForValidation(): void
    {

        $this->merge([

            'email' => Str::lower(trim($this->email)),

        ]);

    }
}