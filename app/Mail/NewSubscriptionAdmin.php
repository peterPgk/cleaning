<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewSubscriptionAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    const MAIL_NAME = "NewSubscriptionAdmin";


    /**
     * Create a new message instance.
     *
     * @param $company
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.company-subscribed-to-admin')
            ->from(env('EMAIL_NOREPLAY')->subject($this->company->name.' has just registered on compare.ofertiko.com'))
            ;
    }
}
