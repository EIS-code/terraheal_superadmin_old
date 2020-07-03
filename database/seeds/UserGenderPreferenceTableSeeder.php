<?php

use Illuminate\Database\Seeder;
use App\UserGenderPreference;

class UserGenderPreferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserGenderPreference::insert([
            'id'   => 1,
            'name' => 'No preferences'
        ]);

        UserGenderPreference::insert([
            'id'   => 2,
            'name' => 'Prefer male'
        ]);

        UserGenderPreference::insert([
            'id'   => 3,
            'name' => 'Male only'
        ]);

        UserGenderPreference::insert([
            'id'   => 4,
            'name' => 'Prefer female'
        ]);

        UserGenderPreference::insert([
            'id'   => 5,
            'name' => 'Female only'
        ]);
    }
}
