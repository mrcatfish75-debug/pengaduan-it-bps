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
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        ActivityLog::create([
            'user_id'    => $user->id,
            'aksi'       => 'LOGIN',
            'model'      => 'User',
            'model_id'   => $user->id,
            'deskripsi'  => 'User login sebagai ' . $user->role,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route(match ($user->role) {
            'admin'   => 'admin.dashboard',
            'kasubag' => 'kasubag.dashboard',
            default   => 'pegawai.dashboard',
        });
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::create([
                'user_id'    => $user->id,
                'aksi'       => 'LOGOUT',
                'model'      => 'User',
                'model_id'   => $user->id,
                'deskripsi'  => 'User logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}