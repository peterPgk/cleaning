<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GettingStarted extends Mailable
{
    use Queueable, SerializesModels;

    const MAIL_NAME = "GettingStarted";
    public $company;
    public $plan;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company,$plan,$password)
    {
        $this->company = $company;
        $this->plan = $plan;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.getting-started')
            ->from(env('EMAIL_NOREPLAY'))->subject('Getting started with compare.ofertiko.com')
            ;
    }
}
