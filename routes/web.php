<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\BarangController;

use App\Http\Controllers\Pegawai\LaporanController as PegawaiController;
use App\Http\Controllers\Kasubag\LaporanController as KasubagController;


/*
|--------------------------------------------------------------------------
| ROOT (FIXED)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route('dashboard');

});


/*
|--------------------------------------------------------------------------
| SMART DASHBOARD REDIRECT (FIXED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {

    $user = Auth::user();

    return match ($user->role) {

        'admin'   => redirect()->route('admin.dashboard'),
        'pegawai' => redirect()->route('pegawai.dashboard'),
        'kasubag' => redirect()->route('kasubag.dashboard'),

        default   => redirect()->route('login'),

    };

})->name('dashboard');


/*
|--------------------------------------------------------------------------
| ================= ADMIN AREA =================
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class,'index'])
            ->name('dashboard');

        Route::get('/laporan', [AdminLaporanController::class,'index'])
            ->name('laporan');

        Route::get('/laporan/{id}', [AdminLaporanController::class,'show'])
            ->whereNumber('id')
            ->name('laporan.show');

        Route::get('/laporan/{id}/edit', [AdminLaporanController::class,'edit'])
            ->whereNumber('id')
            ->name('laporan.edit');

        Route::put('/laporan/{id}', [AdminLaporanController::class,'update'])
            ->whereNumber('id')
            ->name('laporan.update');

        Route::delete('/laporan/{id}', [AdminLaporanController::class,'destroy'])
            ->whereNumber('id')
            ->name('laporan.destroy');

        Route::post('/laporan/{id}/verifikasi', [AdminLaporanController::class,'verifikasi'])
            ->whereNumber('id')
            ->name('laporan.verifikasi');

        Route::post('/laporan/{id}/selesai', [AdminLaporanController::class,'selesai'])
            ->whereNumber('id')
            ->name('laporan.selesai');

        Route::get('/activity-log', [ActivityLogController::class,'index'])
            ->name('activity-log');

        Route::post('/import-barang', [BarangController::class,'import'])
            ->name('import.barang');

        Route::get('/pengguna', [UserController::class,'index'])
            ->name('pengguna');

        Route::get('/pengguna/create', [UserController::class,'create'])
            ->name('pengguna.create');

        Route::post('/pengguna', [UserController::class,'store'])
            ->name('pengguna.store');

        Route::get('/pengguna/{id}/edit', [UserController::class,'edit'])
            ->whereNumber('id')
            ->name('pengguna.edit');

        Route::put('/pengguna/{id}', [UserController::class,'update'])
            ->whereNumber('id')
            ->name('pengguna.update');

        Route::delete('/pengguna/{id}', [UserController::class,'destroy'])
            ->whereNumber('id')
            ->name('pengguna.destroy');

});


/*
|--------------------------------------------------------------------------
| ================= PEGAWAI AREA =================
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','pegawai'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {

        Route::get('/dashboard', [PegawaiController::class,'dashboard'])
            ->name('dashboard');

        Route::get('/lapor', [PegawaiController::class,'create'])
            ->name('lapor.create');

        Route::post('/lapor', [PegawaiController::class,'store'])
            ->name('lapor.store');

        Route::get('/laporan-saya', [PegawaiController::class,'myReports'])
            ->name('laporan_saya');

});


/*
|--------------------------------------------------------------------------
| ================= KASUBAG AREA =================
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','kasubag'])
    ->prefix('kasubag')
    ->name('kasubag.')
    ->group(function () {

        Route::get('/dashboard', [KasubagController::class,'index'])
            ->name('dashboard');

        Route::get('/laporan/{id}', [KasubagController::class,'show'])
            ->whereNumber('id')
            ->name('laporan.show');

        Route::get('/laporan/{id}/edit', [KasubagController::class,'edit'])
            ->whereNumber('id')
            ->name('laporan.edit');

        Route::put('/laporan/{id}', [KasubagController::class,'update'])
            ->whereNumber('id')
            ->name('laporan.update');

        Route::post('/laporan/{id}/putusan', [KasubagController::class,'putusan'])
            ->whereNumber('id')
            ->name('putusan');

        Route::get('/hasil-laporan', [KasubagController::class,'hasil'])
            ->name('hasil');

        Route::get('/kelola-barang', [KasubagController::class,'barang'])
            ->name('barang');

        Route::patch('/kelola-barang/{id}', [KasubagController::class,'updateBarang'])
            ->whereNumber('id')
            ->name('barang.update');

        Route::delete('/kelola-barang/{id}', [KasubagController::class,'destroyBarang'])
            ->whereNumber('id')
            ->name('barang.destroy');

});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class,'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class,'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class,'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| FALLBACK (PENTING BANGET)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return redirect()->route('dashboard');
});


/*
|--------------------------------------------------------------------------
| AUTH (BREEZE)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';