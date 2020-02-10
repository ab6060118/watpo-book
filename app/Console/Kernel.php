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
        Commands\Inspire::class,
        Commands\ReportStatus::class,
        Commands\SendReportSMS::class,
        Commands\MarkFinishedOrder::class,
        Commands\unlockBlackList::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
        $schedule->call('App\Http\Controllers\SmsController@schedulingSendReportSMS')->everyMinute();
        $schedule->call('App\Http\Controllers\ReportController@FinishedService')->everyMinute();
        $schedule->command('order:MarkFinishedOrder')->dailyAt('05:00');
        $schedule->command('blackList:unlock')->dailyAt('05:00');
        $schedule->command('backup:run --only-db')->dailyAt('05:00');
        $schedule->command('backup:clean')->dailyAt('05:00');
    }
}
