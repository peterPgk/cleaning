<?php

namespace App\Console\Commands;

use App\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CountLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:count-limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
                $orders_cnt = $company->orders()->where('created_at','>=',Carbon::now()->toDateString().' 00:00:01')->where('created_at','<=',Carbon::now()->toDateString().' 23:59:59')->get()->count();
                if($orders_cnt == 5) {
                    \Mail::to($company->email)->send(new \App\Mail\CountLimit(5));
                }elseif($orders_cnt == 10) {
                    \Mail::to($company->email)->send(new \App\Mail\CountLimit(10));
                }
        });
    }
}
