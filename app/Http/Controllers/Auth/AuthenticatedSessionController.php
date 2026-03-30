<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\ActivityLog;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate (sudah ada rate limit di LoginRequest)
        $request->authenticate();

        // Prevent session fixation
        $request->session()->regenerate();

        // ✅ LOG LOGIN BERHASIL
        ActivityLog::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'LOGIN_BERHASIL',
            'model'     => 'User',
            'model_id'  => Auth::id(),
            'deskripsi' => 'User berhasil login',
            'ip_address'=> $request->ip(),
            'user_agent'=> $request->userAgent(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ✅ LOG LOGOUT
        if (Auth::check()) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aksi'      => 'LOGOUT',
                'model'     => 'User',
                'model_id'  => Auth::id(),
                'deskripsi' => 'User logout',
                'ip_address'=> $request->ip(),
                'user_agent'=> $request->userAgent(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}