<?php

namespace App\Mail;

use App\ServiceCategories;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerQuoteReserved extends Mailable
{
    use Queueable, SerializesModels;


    public $link;
    public $customer_name;
    public $category_name;
    public $price;
    const MAIL_NAME = "CustomerQuoteReserved";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($uuid, $customer)
    {
        $this->customer_name = $customer->firstname . ' ' . $customer->lastname;
        $this->link = config('app.restore_order_link') . $uuid;
        $this->category_name = ServiceCategories::where('id', $customer->services)->pluck('name')->first();
        // TODO: Lowets price in the subject

        $this->price = $this->getPrice($customer);
    }

    protected function getPrice($customer) {

        $services = [];
        if (isset($customer->selected_services)) {
            foreach ($customer->selected_services as $service => $cnt) {
                if (strstr($service, 'services_')) {
                    $service_id = (int)substr($service, 9);
                    $services[] = ['service_id' => $service_id, 'count' => $cnt];
                }
            }
            $company = \App\Company::getCompanyWithLowestPrice($services, $customer->service_date, $customer->postcode);
            return \App\Company::getLowestPrice($company);
        }
        return 0;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.customer-quote-reserved')->from(env('EMAIL_NOREPLAY'))->subject($this->customer_name.', our best price for '.$this->category_name.' - '.$this->price);
    }
}
