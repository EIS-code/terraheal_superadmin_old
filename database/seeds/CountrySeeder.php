<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::insert([
            'name'       => 'India',
            'short_name' => 'India',
            'iso3'       => 'IND'
        ]);
    }
}
