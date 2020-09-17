<?php

use Illuminate\Database\Seeder;
use App\FocusArea;

class FocusAreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FocusArea::insert([
            'name' => 'Head'
        ]);

        FocusArea::insert([
            'name' => 'Neck'
        ]);

        FocusArea::insert([
            'name' => 'Back'
        ]);

        FocusArea::insert([
            'name' => 'Leg'
        ]);

        FocusArea::insert([
            'name' => 'Shoulder'
        ]);

        FocusArea::insert([
            'name' => 'Hand'
        ]);
    }
}
