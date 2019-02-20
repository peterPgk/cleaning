<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerFeedback extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $link;
    public $company_name;
    public $review_link;
    public $company_logo;

    const MAIL_NAME = 'CustomerFeedback';

    /**
     * Create a new message instance.
     *
     * @param $company
     * @param $data
     */
    public function __construct($company, $uuid)
    {
        $this->company = $company;
        $this->company_logo = $company->logo;
        $this->link = 'http://compare.ofertiko.com/rating/' . $uuid;
        $this->review_link = $this->link;
        $this->company_name = $company->name;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.customer-feedback')->from(env('EMAIL_NOREPLAY'))->
        subject('Tell us what you think about ' . $this->company->name);
    }
}
