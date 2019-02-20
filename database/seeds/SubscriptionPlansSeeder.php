<?php

use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            'bronze' => ['price' => '50','default' => 1],
            'silver' => ['price' => '100','default' => 0],
            'gold' => ['price'=>'150', 'default' => 0],
        ];

        foreach ($plans as $plan => $data) {
            \App\SubscriptionPlan::create(['stripe_plan' => $plan,'name' => ucfirst($plan),
                'price' => $data['price'],'default' => $data['default']]);
        }
    }
}
