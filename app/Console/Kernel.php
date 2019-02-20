<?php

namespace App\Console;

use App\Console\Commands\CountLimit;
use App\Console\Commands\CustomerFeedback;
use App\Console\Commands\CustomerQuoteReserved;
use App\Console\Commands\LimitExpire;
use App\Console\Commands\NeedHelpCron;
use App\Console\Commands\NewInvoice;
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
        LimitExpire::class,
        NeedHelpCron::class,
        CountLimit::class,
        CustomerQuoteReserved::class,
        NewInvoice::class,
        CustomerFeedback::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('cron:limit-expire')
            ->twiceDaily(13,18);

        $schedule->command('cron:need-help')
            ->dailyAt('18:00');

        $schedule->command('cron:count-limit')
            ->everyTenMinutes();

        $schedule->command('cron:quote-reserved')
            ->hourly();

        $schedule->command('cron:company-new-invoice')->monthlyOn(1,'15:00');
        $schedule->command('cron:customer-feedback')->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');

    }
}
