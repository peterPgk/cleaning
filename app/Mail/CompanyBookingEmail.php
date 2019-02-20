<?php

namespace App\Mail;

use Carbon\Carbon;
use App\ServiceCategories;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $order_data;
    public $total_sum;
    public $booking_fee;
    public $date;
    public $timeslot_from;
    public $timeslot_to;
    public $category_name;

    const MAIL_NAME = "CompanyBookingEmail";


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order,$order_data,$total_sum,$booking_fee)
    {
        $this->order = $order;
        $this->order_data = $order_data;
        $this->total_sum = $total_sum/100;
        $this->booking_fee = $booking_fee/100;
        $this->date = Carbon::parse($order_data->service_date)->format('d/m/Y');
        $aTimelstos = explode('|', $order_data->timeslots);

        $this->timeslot_from = Carbon::parse($aTimelstos[0])->format('H:00');
        $this->timeslot_to = Carbon::parse($aTimelstos[1])->format('H:00');
        
        
        $this->category_name = ServiceCategories::where('id', $order_data->services)->pluck('name')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.company-order')->from(env('EMAIL_NOREPLAY'))->subject("You've received a new booking ({$this->order->id}) from compare.ofertiko.com)");
    }
}
