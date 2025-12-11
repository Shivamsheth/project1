<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clear expired OTPs every 10 minutes
        $schedule->command('otp:clear-expired')
            ->everyTenMinutes()
            ->withoutOverlapping();

        // Optional: Log cleanup activity
        // $schedule->command('otp:clear-expired')->everyTenMinutes()->sendOutputTo(storage_path('logs/otp-cleanup.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
