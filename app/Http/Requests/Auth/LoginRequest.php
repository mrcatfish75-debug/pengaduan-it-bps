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
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required','string','email'],
            'password' => ['required','string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email','password'), $this->boolean('remember'))) {

            // 15 menit lock window
            RateLimiter::hit($this->throttleKey(), 900);

            ActivityLog::record(
                'LOGIN_GAGAL',
                'User',
                null,
                'Login gagal untuk email: '.$this->email
            );

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Reset counter jika berhasil
        RateLimiter::clear($this->throttleKey());

        ActivityLog::record(
            'LOGIN_BERHASIL',
            'User',
            Auth::id(),
            'User berhasil login'
        );
    }

    public function ensureIsNotRateLimited(): void
    {
        $maxAttempts = 5;

        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        ActivityLog::record(
            'IP_LOCKED',
            'User',
            null,
            'IP dikunci karena terlalu banyak percobaan login'
        );

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::lower($this->string('email')).'|'.$this->ip();
    }
}