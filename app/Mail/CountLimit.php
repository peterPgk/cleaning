<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CountLimit extends Mailable
{
    use Queueable, SerializesModels;

    public $count;

    const MAIL_NAME = "CountLimit";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($count)
    {
        $this->count = $count;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.count-limit')->from(env('EMAIL_NOREPLAY'))->subject('Alert: You have '.$this->count.' bookings left on your package');
    }
}
