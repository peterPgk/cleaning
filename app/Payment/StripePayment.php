<?php namespace App\Payment;


use App\Company;

class StripePayment implements PaymentInterface
{


    public function clientPayment()
    {
    }

    public function companySubscription(Company $company)
    {

    }
}