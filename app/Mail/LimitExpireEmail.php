<?php

namespace App\Mail;

use App\Http\Controllers\CompanySignUpController;
use App\Company;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LimitExpireEmail extends Mailable
{
    use Queueable, SerializesModels;

    const MAIL_NAME = "LimitExpireEmail";

    public $company;
    public $plan;
    public $reset_date;
    public $nextplan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
        
        $this->plan = $company->getMainAccount()->getCompanySubscriptionPlan();
        
        $this->reset_date =  $this->plan->updated_at->addMonths(1)->format('d/m/Y'); 
        //TODO later refactor if
        if($this->plan->stripe_name == 'pro'){
            $this->nextplan = null;
        } else {
            $biggerplans = (new CompanySignUpController)->getBiggerPlans();
            
           // print_r($biggerplans);die;
            $this->nextplan = last($biggerplans['plans']);
            
        }
        
      //  print_r($this->plan);
       // print_r($this->reset_date);
       // print_r($this->nextplan);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.limit-expire')
            ->from(env('EMAIL_NOREPLAY'))
            ;
    }
}
