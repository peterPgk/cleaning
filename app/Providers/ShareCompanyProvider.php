<?php

namespace App\Providers;

use App\Company;
use Illuminate\Support\ServiceProvider;

class ShareCompanyProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function($view) {
            if(auth()->check()) {
                $company = Company::getMainAccount();

                $p = $company->getCompanySubscriptionPlan();

                $plan = [
                	'jobs' => $p->jobs_limit,
                	'name' => $p->name,
	                'plan' => $p->stripe_plan
                ];

                $view->with('company', $company)->with('plan', $plan);
            }
        });
//        dd(auth()->check());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
