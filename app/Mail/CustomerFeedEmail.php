<?php

namespace App\Mail;

use App\MasterSettigns;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerFeedEmail extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $company_name;
    public $review_link;
    public $company_logo;

    public function __construct($company_name,$company_logo,$review_link)
    {
//        throw new \Exception(collect((array)$order_data));
        $this->company_name = $company_name;
        $this->company_logo = $company_logo;
        $this->review_link = $review_link;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
    $address = 'no-reply@compare.ofertiko.com';
    $subject = 'Tell us what you think about '.$this->company_name;

    return $this->view('email.customer-feedback')
                ->from(env('EMAIL_NOREPLAY'))
                ->subject($subject);
    }
}
