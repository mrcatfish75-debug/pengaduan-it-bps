<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pegawai\LaporanController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;
use App\Http\Controllers\Kasubag\LaporanController as KasubagLaporan;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\ActivityLog;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});
/*
|--------------------------------------------------------------------------
| ROUTE UMUM (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {

        return match (auth()->user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'kasubag' => redirect()->route('kasubag.dashboard'),
            default   => redirect()->route('pegawai.dashboard'),
        };

    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ROUTE PEGAWAI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','pegawai'])
    ->prefix('pegawai')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('pegawai.dashboard');
        })->name('pegawai.dashboard');

        Route::get('/lapor', [LaporanController::class,'create'])
            ->name('lapor.create');

        Route::post('/lapor', [LaporanController::class,'store'])
            ->name('lapor.store');

        Route::get('/laporan-saya', [LaporanController::class,'myReports'])
            ->name('pegawai.laporan_saya');
});

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN (TIM IT)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','admin','admin.ip'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/laporan', [AdminLaporan::class,'index'])
            ->name('admin.laporan');

        Route::get('/laporan/{id}', [AdminLaporan::class,'show'])
            ->name('admin.laporan.show');

        Route::post('/laporan/{id}/verifikasi', [AdminLaporan::class,'verifikasi'])
            ->name('admin.laporan.verifikasi');

        Route::post('/laporan/{id}/hasil', [AdminLaporan::class,'simpanHasil'])
            ->name('admin.laporan.hasil');

        // 🔒 Import Barang (dengan rate limit)
        Route::post('/import-barang', [BarangController::class, 'import'])
            ->middleware('throttle:5,1')
            ->name('import.barang');

        // 🔒 Activity Log
        Route::get('/activity-log', function () {
            $logs = ActivityLog::with('user')
                ->latest()
                ->paginate(15);

            return view('admin.activity-log.index', compact('logs'));
        })->name('admin.activity-log');

        
});

/*
|--------------------------------------------------------------------------
| ROUTE KASUBAG
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','kasubag'])
    ->prefix('kasubag')
    ->group(function () {

        Route::get('/dashboard', [KasubagLaporan::class,'index'])
            ->name('kasubag.dashboard');

        Route::post('/laporan/{id}/putusan', [KasubagLaporan::class,'putusan'])
            ->name('kasubag.putusan');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';