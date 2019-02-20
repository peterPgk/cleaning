<?php

namespace App\Mail;

use App\MasterSettigns;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewSubscriptionEmail extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $company_name;
    public $package_name;

    public function __construct($company_name,$package_name)
    {
//        throw new \Exception(collect((array)$order_data));
        $this->company_name = $company_name;
        $this->package_name = $package_name;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
    $address = 'no-reply@compare.ofertiko.com';
    $name = 'Compare.ofertiko.com';
    $subject = $this->company_name.' has just joined Compare.ofertiko.com';

    return $this->view('email.new-subscription')
        ->from(env('EMAIL_NOREPLAY'))
        ->subject($subject);
    }
}
