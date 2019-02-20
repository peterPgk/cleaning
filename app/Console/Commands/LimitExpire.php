<?php

namespace App\Console\Commands;

use App\Company;
use App\Mail\LimitExpireEmail;
use App\TempData;
use Illuminate\Console\Command;

class LimitExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:limit-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Company limit expire';

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
        $companies = Company::all();

        $companies->each(function(Company $company){
            $limit = $company->getSubscriptionLimit();
            $today_orders = $company->getTodayBookings();
            if(($limit - $today_orders) <= 0) {
                if(!$company->isSendEmail(LimitExpireEmail::MAIL_NAME)) {
                    \Mail::to($company->email)->send(new LimitExpireEmail($company));
                    $company->emails()->create(['email_name'=>LimitExpireEmail::MAIL_NAME]);
                }
            }
        });
    }
}
