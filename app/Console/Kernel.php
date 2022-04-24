<?php

namespace App\Console;

use App\Console\Commands\CheckPrices;
use App\Console\Commands\FetchGames;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(FetchGames::class)
            ->withoutOverlapping(10)
            ->runInBackground()
            ->dailyAt('1:00')
            ->days([Schedule::MONDAY, Schedule::THURSDAY]);

        $schedule->command(CheckPrices::class)
            ->withoutOverlapping(15)
            ->runInBackground()
            ->everyFourHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
