<?php

use Illuminate\Database\Seeder;
use App\Shop;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::insert([
            'name'                => 'Shop - 01',
            'address'             => 'Gondal, Rajkot, Gujarat, India 360 311',
            'longitude'           => '21.9612',
            'latitude'            => '70.7939',
            'owner_name'          => 'Jaydeep Mor',
            'tel_number'          => '+91 7874808074',
            'owner_mobile_number' => '+91 7874808074',
            'owner_email'         => 'it.jaydeep.mor@gmail.com',
            'email'               => 'it.jaydeep.mor@gmail.com',
            'time_zone'           => 'Asia/Calcutta',
            'user_name'           => 'jaydeep-mor',
            'city_id'             => 1,
            'country_id'          => 1,
            'currency_id'         => 1
        ]);
    }
}
