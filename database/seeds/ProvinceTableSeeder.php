<?php

use Illuminate\Database\Seeder;
use App\Province;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::insert([
            'name'       => 'Gujarat',
            'country_id' => 1
        ]);
    }
}
