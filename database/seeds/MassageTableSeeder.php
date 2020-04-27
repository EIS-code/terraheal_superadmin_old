<?php

use Illuminate\Database\Seeder;
use App\Massage;

class MassageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Massage::insert([
            'name'  => 'Thai Massage',
            'image' => ''
        ]);

        Massage::insert([
            'name'  => 'Deep Tissue Sport Massage',
            'image' => ''
        ]);

        Massage::insert([
            'name'  => 'Head, Neck & Shoulders Massage',
            'image' => ''
        ]);

        Massage::insert([
            'name'  => 'Hot Stones Massage',
            'image' => ''
        ]);

        Massage::insert([
            'name'  => 'Terra Heal Massage',
            'image' => ''
        ]);

        Massage::insert([
            'name'  => 'Couple Massage',
            'image' => ''
        ]);
    }
}
