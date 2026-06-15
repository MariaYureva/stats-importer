<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stats:sync orders')->hourly()->withoutOverlapping();
        $schedule->command('stats:sync sales')->hourly()->withoutOverlapping();
        $schedule->command('stats:sync incomes')->dailyAt('03:00')->withoutOverlapping();
        $schedule->command('stats:sync stocks')->everyThreeHours()->withoutOverlapping();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
