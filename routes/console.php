<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\ActivityLog;

/*
|--------------------------------------------------------------------------
| Console Commands
|--------------------------------------------------------------------------
|
| File ini digunakan untuk mendefinisikan Artisan command berbasis closure
| dan juga scheduler untuk menjalankan task otomatis.
|
*/


/*
|--------------------------------------------------------------------------
| Default Laravel Command
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {

    $this->comment(Inspiring::quote());

})->purpose('Display an inspiring quote');



/*
|--------------------------------------------------------------------------
| CLEANUP ACTIVITY LOG (AUTO MAINTENANCE)
|--------------------------------------------------------------------------
|
| Menghapus activity log yang berusia lebih dari 90 hari
| agar database tidak membengkak.
|
| Standar enterprise logging retention.
|
*/

Artisan::command('logs:cleanup', function () {

    $deleted = ActivityLog::where(
        'created_at',
        '<',
        now()->subDays(90)
    )->delete();

    $this->info("Activity log lama dihapus: {$deleted} data");

})->purpose('Delete activity logs older than 90 days');



/*
|--------------------------------------------------------------------------
| SCHEDULER
|--------------------------------------------------------------------------
|
| Laravel akan menjalankan scheduler ini melalui:
|
| php artisan schedule:run
|
| Biasanya dipanggil setiap menit oleh CRON.
|
*/

Schedule::command('logs:cleanup')->daily();