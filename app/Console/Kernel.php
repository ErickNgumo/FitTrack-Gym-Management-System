<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * On XAMPP/Windows, trigger via Windows Task Scheduler running:
     *   php artisan schedule:run
     * every minute, OR run the command manually each morning.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Expire past subscriptions every night at midnight
        $schedule->command('fittrack:expire-subscriptions')->dailyAt('00:05');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
