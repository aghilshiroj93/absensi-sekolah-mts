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
        // Mark students as absent at 15:00 (3 PM) every weekday
        $schedule->command('absensi:mark-absent')
                ->weekdays()
                ->at('15:00')
                ->withoutOverlapping() // Prevent multiple runs
                ->runInBackground()    // Don't block other tasks
                ->emailOutputOnFailure(env('MAIL_ADMIN_ADDRESS')); // Optional: notify admin on failure
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
