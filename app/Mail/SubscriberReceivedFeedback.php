<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriberReceivedFeedback extends Mailable
{
    use Queueable, SerializesModels;
 
    public $company;
    public $rating;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company,$rating)
    {
        $this->company = $company;
        $this->rating = $rating;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.subscriber-received-feedback')
            ->from(env('EMAIL_NOREPLAY'))->subject($this->company->name.', you have received feedback on compare.ofertiko.com')
            ;
    }
}
