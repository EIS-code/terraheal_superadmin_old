<?php

use Illuminate\Database\Seeder;
use App\MassageTiming;

class MassageTimingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MassageTiming::insert([
            'time'       => '60',
            'massage_id' => 1
        ]);

        MassageTiming::insert([
            'time'       => '90',
            'massage_id' => 1
        ]);

        MassageTiming::insert([
            'time'       => '30',
            'massage_id' => 2
        ]);

        MassageTiming::insert([
            'time'       => '60',
            'massage_id' => 3
        ]);

        MassageTiming::insert([
            'time'       => '90',
            'massage_id' => 4
        ]);

        MassageTiming::insert([
            'time'       => '120',
            'massage_id' => 5
        ]);
    }
}
