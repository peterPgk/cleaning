<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanyServicesCategoriesSeeder::class);
        $this->call(CompanyAssociationsSeeder::class);
        $this->call(SubscriptionPlansSeeder::class);
        $this->call(ServicesSeeder::class);
    }
}
