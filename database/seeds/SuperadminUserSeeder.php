<?php

use App\Superadmin;
use Illuminate\Database\Seeder;

class SuperadminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Superadmin::create([
            'id'       => 1,
            'name'     => 'Superadmin',
            'email'    => 'viren@evolutionitsolution.com',
            'password' => (env('APP_ENV') == 'local') ? Hash::make('Shiv@Terraheal') : Hash::make('Portugal@123')
        ]);
    }
}
