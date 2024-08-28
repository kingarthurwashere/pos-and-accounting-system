<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(function () {
        //     DB::table('remittances')->delete();
        // })->everyFiveSeconds();
        $schedule->command('queue:work --tries=3')
            ->everyMinute();

        //Pull remittances
        $schedule->command('db:seed RemittanceDailySeeder')
            ->everyTwoMinutes();

        //Pull orders
        $schedule->command('db:seed OrderDailySeeder')
            ->everyTwoMinutes();

        //Close daily-reports
        $schedule->command('daily-report:close')
            ->everyThreeMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
