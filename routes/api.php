<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\DashboardApiController;
use App\Http\Controllers\Api\Admin\LaporanApiController;
use App\Http\Controllers\Api\Auth\AuthApiController;

/*
|--------------------------------------------------------------------------
| PUBLIC API
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json([
        'message' => 'API working'
    ]);
});


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [
    DashboardApiController::class,
    'index'
]);


/*
|--------------------------------------------------------------------------
| ADMIN LAPORAN
|--------------------------------------------------------------------------
*/

Route::get('/admin/laporan', [
    LaporanApiController::class,
    'index'
]);

Route::get('/admin/laporan/{id}', [
    LaporanApiController::class,
    'show'
]);

Route::post('/admin/laporan/{id}/verifikasi', [
    LaporanApiController::class,
    'verifikasi'
]);

/*
|--------------------------------------------------------------------------
| AUTH API
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthApiController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){

    Route::get('/me',[AuthApiController::class,'me']);
    Route::post('/logout',[AuthApiController::class,'logout']);

});