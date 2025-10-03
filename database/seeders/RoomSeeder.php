<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create(['room_number' => 'L205', 'building_id' => 1]);
        Room::create(['room_number' => 'L301', 'building_id' => 1]);
        Room::create(['room_number' => 'L302', 'building_id' => 1]);
        Room::create(['room_number' => 'L303', 'building_id' => 1]);
        Room::create(['room_number' => 'L304', 'building_id' => 1]);
        Room::create(['room_number' => 'L305', 'building_id' => 1]);
        Room::create(['room_number' => 'V201', 'building_id' => 2]);
        Room::create(['room_number' => 'V202', 'building_id' => 2]);
        Room::create(['room_number' => 'V203', 'building_id' => 2]);
        Room::create(['room_number' => 'V204', 'building_id' => 2]);
        Room::create(['room_number' => 'V205', 'building_id' => 2]);
    }
}
