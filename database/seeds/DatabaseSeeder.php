<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(CountryTableSeeder::class);
        // $this->call(ProvinceTableSeeder::class);
        // $this->call(CityTableSeeder::class);
        /* $this->call(CurrencyTableSeeder::class);
        $this->call(MassageTableSeeder::class);
        $this->call(MassageTimingTableSeeder::class);
        $this->call(MassagePriceTableSeeder::class);
        $this->call(ShopTableSeeder::class);
        $this->call(RoomTableSeeder::class); */
		$this->call(MassagePreferenceTableSeeder::class);

        Model::reguard();
    }
}
