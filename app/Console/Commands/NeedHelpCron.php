<?php

namespace App\Console\Commands;

use App\Company;
use App\Mail\NeedHelp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NeedHelpCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:need-help';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Need help email cron';

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
        $companies = Company::all()->where('data_collected', '=', 0)->where('created_at','<',Carbon::now()->toDateString().' 00:00:00');
        $companies->each(function (Company $company) {
            if (!$company->isSendEmail(NeedHelp::MAIL_NAME)) {
                \Mail::to($company->email)->send(new NeedHelp());
                $company->emails()->create(['email_name' => NeedHelp::MAIL_NAME]);
            }
        });
    }
}
