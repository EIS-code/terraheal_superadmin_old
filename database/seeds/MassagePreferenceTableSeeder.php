<?php

use Illuminate\Database\Seeder;
use App\MassagePreference;
use App\MassagePreferenceOption;

class MassagePreferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MassagePreference::insert([
			'id'   => 1,
            'name' => 'Pressure'
        ]);

		MassagePreference::insert([
			'id'   => 2,
            'name' => 'Gender Preference'
        ]);

		MassagePreference::insert([
			'id'   => 3,
            'name' => 'Which areas needs treatements ?'
        ]);

		MassagePreference::insert([
			'id'   => 4,
            'name' => 'Muscles, Joints, Tendons, Discs, Bones, Nerve problems ?'
        ]);

		MassagePreference::insert([
			'id'   => 5,
            'name' => 'Past surgeries, fractures, accidents ?'
        ]);

		MassagePreference::insert([
			'id'   => 6,
            'name' => 'Skin, Hair, Nail problems and allergies ?'
        ]);

		MassagePreference::insert([
			'id'   => 7,
            'name' => 'Any health conditions or diseases ?'
        ]);

		MassagePreferenceOption::insert([
			'name'					=> 'Soft pressure',
			'massage_preference_id' => 1
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Medium pressure',
			'massage_preference_id' => 1
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Strong pressure',
			'massage_preference_id' => 1
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Extra strong pressure',
			'massage_preference_id' => 1
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'No preference',
			'massage_preference_id' => 2
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Prefer male',
			'massage_preference_id' => 2
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Male only',
			'massage_preference_id' => 2
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Prefer female',
			'massage_preference_id' => 2
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Female only',
			'massage_preference_id' => 2
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Which areas needs treatements ?',
			'massage_preference_id' => 3
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Muscles, Joints, Tendons, Discs, Bones, Nerve problems ?',
			'massage_preference_id' => 4
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Past surgeries, fractures, accidents ?',
			'massage_preference_id' => 5
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Skin, Hair, Nail problems and allergies ?',
			'massage_preference_id' => 6
		]);

		MassagePreferenceOption::insert([
			'name'					=> 'Any health conditions or diseases ?',
			'massage_preference_id' => 7
		]);
    }
}
