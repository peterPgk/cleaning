<?php

use App\Service;
use App\ServiceCategories;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $services =[
            'Carpet Cleaning' => [
                'single bedroom',
                'double bedroom',
                'lounge/living room',
                'dining room',
                'hallway',
                'landing',
            ],
            'Upholstery Steam Cleaning' => [
                'armchair',
                '1-seater sofa',
                '2-seater sofa',
                '3-seater sofa',
                'foot stool',
                'Curtain half'
            ]
        ];

        foreach ($services as $category_name => $service) {
            $category = ServiceCategories::where('name','=',$category_name)->first();
            foreach ($service as $service_name) {
                Service::create(['service_category_id' => $category->id,'name' => $service_name]);
            }
        }

    }
}
