<?php

use Illuminate\Database\Seeder;
use App\City;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::insert([
            'name'        => 'Rajkot',
            'province_id' => 1,
            'country_id'  => 1
        ]);

        City::insert([
            'name'        => 'Baroda',
            'province_id' => 1,
            'country_id'  => 1
        ]);
    }
}
