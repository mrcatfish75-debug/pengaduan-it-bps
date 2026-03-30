<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;

class CleanupActivityLogs extends Command
{
    /**
     * Command name
     */
    protected $signature = 'logs:cleanup';

    /**
     * Command description
     */
    protected $description = 'Hapus activity log yang lebih dari 90 hari';

    /**
     * Execute the command
     */
    public function handle()
    {
        $deleted = ActivityLog::where(
            'created_at',
            '<',
            now()->subDays(90)
        )->delete();

        $this->info("Activity log lama dihapus: {$deleted} data");

        return Command::SUCCESS;
    }
}