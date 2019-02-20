<?php

use App\ServiceCategories;
use Illuminate\Database\Seeder;

class CompanyServicesCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            'End of tenancy cleaning' => [
                'Tenancy cleaning services'
            ],
            'Window cleaning' => [],
            'Carpet cleaning' => [
                'Carpet Cleaning',
                'Upholstery Steam Cleaning'
            ],
            'After builders cleaning' => [],
            'Rubbish removal'=> [],
            'Handyman'=> [],
            'Removal services'=> [],
        ];
        foreach ($services as $main_service => $sub_services) {
           $tmp_main = ServiceCategories::create(['name' => $main_service]);
           if(!empty($sub_services)) {
               foreach ($sub_services as $sub_service)
               ServiceCategories::create(['parent_id'=> $tmp_main->id,'name' => $sub_service]);
           }
        }
    }
}
