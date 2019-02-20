<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;

class NewInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:company-new-invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monthly invoices';

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
        $companies = Company::getAllMainActive();
        $companies->each(function (Company $company) {
            if (!$company->isSendEmail(\App\Mail\NewInvoice::MAIL_NAME)) {
                \Mail::to($company->email)->send(new \App\Mail\NewInvoice($company->invoices()->last()->id));
                $company->emails()->create(['email_name' => \App\Mail\NewInvoice::MAIL_NAME]);
            }
        });
    }
}
