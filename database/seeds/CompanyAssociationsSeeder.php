<?php

use App\Association;
use Illuminate\Database\Seeder;

class CompanyAssociationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $associations = [
            'First association',
            'Second association',
            'Third association',
            'Fourth association',
        ];
        foreach ($associations as $association) {
            Association::create(['name' => $association]);
        }
    }
}
