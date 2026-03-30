<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanPengaduan;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {

            $notifikasi = collect();

            if (Auth::check()) {

                $user = Auth::user();

                if ($user->role === 'admin') {

                    $notifikasi = LaporanPengaduan::where(
                        'status_laporan',
                        'MENUNGGU_REVIEW_ADMIN'
                    )->latest()->take(5)->get();

                }

                elseif ($user->role === 'kasubag') {

                    $notifikasi = LaporanPengaduan::where(
                        'status_laporan',
                        'MENUNGGU_KEPUTUSAN_KASUBAG'
                    )->latest()->take(5)->get();

                }

                elseif ($user->role === 'pegawai') {

                    $notifikasi = LaporanPengaduan::where(
                        'id_user',
                        $user->id
                    )->latest()->take(5)->get();

                }

            }

            $view->with('notifikasi', $notifikasi);

        });
    }
}