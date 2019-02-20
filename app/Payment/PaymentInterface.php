<?php namespace App\Payment;

use App\Company;

interface PaymentInterface {
    public function companySubscription(Company $company);
    public function clientPayment();
}