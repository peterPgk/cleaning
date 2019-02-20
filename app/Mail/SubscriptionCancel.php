<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionCancel extends Mailable
{
    use Queueable, SerializesModels;

    const MAIL_NAME = "SubscriptionCancel";


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.subscription-cancel')
            ->from(env('EMAIL_BILLING'))->subject('compare.ofertiko.com subscription cancelled');
            ;
    }
}
