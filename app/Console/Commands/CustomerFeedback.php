<?php

namespace App\Console\Commands;

use App\Company;
use App\TempData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CustomerFeedback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:customer-feedback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customer feedback';

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
//        $orders = TempData::getOrders(Carbon::now()->subHour()->toDateTimeString(),Carbon::now()->toDateTimeString());
        $orders = TempData::getFeedbacksForDays(3);

        $orders->each(function(TempData $order){
            $customer = $order->getData();
            $company = Company::find($order->company_id)->first();
            $uuid = $order->uuid;
            if(!$order->isSendEmail(\App\Mail\CustomerFeedback::MAIL_NAME) && isset($customer->email)) {
                \Mail::to($customer->email)->send(new \App\Mail\CustomerFeedback($company,$uuid));
                $order->emails()->create(['email_name'=>\App\Mail\CustomerFeedback::MAIL_NAME]);
            }
        });
    }
}
