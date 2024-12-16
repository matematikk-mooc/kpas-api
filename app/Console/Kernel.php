<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\StorageLinkCommand::class,
        Commands\FetchNsrData::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('fetch_from:nsr')
            // !DEBUG
            // ->dailyAt('02:00')
            ->everyHour()
            ->sentryMonitor(maxRuntime: 120)
            ->runInBackground();
        $schedule->command('fetch_from:canvas')
            // !DEBUG
            // ->dailyAt('03:00')
            ->everyHour()
            ->sentryMonitor(maxRuntime: 120)
            ->runInBackground();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
