<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasjidTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('masjid_types')->insert([
            [
                'id' => 1,
                'name' => 'University',
            ],
            [
                'id' => 2,
                'name' => 'Mosque',
            ],
            [
                'id' => 3,
                'name' => 'Hotel',
            ],
            [
                'id' => 4,
                'name' => 'Tourist Attraction',
            ],
            [
                'id' => 5,
                'name' => 'Hospital',
            ],
            [
                'id' => 6,
                'name' => 'International Airport',
            ],
            [
                'id' => 7,
                'name' => 'Restaurant',
            ],
        ]);
    }
}
