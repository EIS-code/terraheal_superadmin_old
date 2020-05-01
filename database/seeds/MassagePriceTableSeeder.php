<?php

use Illuminate\Database\Seeder;
use App\MassagePrice;

class MassagePriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MassagePrice::insert([
            'massage_id'        => 1,
            'massage_timing_id' => 1,
            'price'             => 120,
            'cost'              => 60
        ]);

        MassagePrice::insert([
            'massage_id'        => 1,
            'massage_timing_id' => 2,
            'price'             => 130,
            'cost'              => 50
        ]);

        MassagePrice::insert([
            'massage_id'        => 2,
            'massage_timing_id' => 3,
            'price'             => 160,
            'cost'              => 50
        ]);

        MassagePrice::insert([
            'massage_id'        => 3,
            'massage_timing_id' => 4,
            'price'             => 180,
            'cost'              => 50
        ]);

        MassagePrice::insert([
            'massage_id'        => 4,
            'massage_timing_id' => 5,
            'price'             => 200,
            'cost'              => 50
        ]);

        MassagePrice::insert([
            'massage_id'        => 5,
            'massage_timing_id' => 6,
            'price'             => 220,
            'cost'              => 80
        ]);
    }
}
