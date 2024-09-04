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
        // IMPORTANT!
        // We're currently triggering the Laracel scheduler
        // at XX:00 every hour. If you want the scheduler to
        // be triggered more often than once every hour, or you
        // want it to be triggered at another minute than :00,
        // you'll need to update the file /docker-prod/laravel-cron
        $schedule->command('fetch_from:nsr')
            ->dailyAt('02:00')->runInBackground();
        $schedule->command('fetch_from:canvas')
            ->dailyAt('03:00')->runInBackground();

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
