<?php

use Illuminate\Database\Seeder;
use App\Currency;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            'code'          => 'INR',
            'exchange_rate' => 78,
            'country_id'    => 1
        ]);
    }
}
