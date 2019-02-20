<?php

namespace App\Console\Commands;

use App\TempData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CustomerQuoteReserved extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:quote-reserved';

    /**
     *
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customer quote reserved';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unfinished = TempData::getUnfinshedOrders(Carbon::now()->subHours(2)->toDateTimeString(), Carbon::now()->subHour()->toDateTimeString());
        $unfinished->each(function(TempData $order){
            $customer = $order->getData();
            $uuid = $order->uuid;
            if(isset($customer->email) && isset($customer->selected_services)){
               if(!$order->isSendEmail(\App\Mail\CustomerQuoteReserved::MAIL_NAME)) {
                    \Mail::to($customer->email)->send(new \App\Mail\CustomerQuoteReserved($uuid,$customer));
                    $order->emails()->create(['email_name'=>\App\Mail\CustomerQuoteReserved::MAIL_NAME]);
               }
            }
        });
    }
}
