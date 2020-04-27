<?php

use Illuminate\Database\Seeder;
use App\Room;

class RoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::insert([
            'name'    => 'Room - 01',
            'shop_id' => 2
        ]);

         Room::insert([
            'name'    => 'Room - 02',
            'shop_id' => 2
        ]);
    }
}
