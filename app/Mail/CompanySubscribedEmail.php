<?php

namespace App\Mail;

use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanySubscribedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $temp_pass;

    const MAIL_NAME = "CompanySubscribedEmail";


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($temp_pass)
    {
        $this->temp_pass = $temp_pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.company-subscribed',['password' => $this->temp_pass])->from(env('EMAIL_NOREPLAY'));
    }
}
