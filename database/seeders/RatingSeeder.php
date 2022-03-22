<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ratings')->insert([
            [
                'id' => 1,
                'name' => 'Buruk',
            ],
            [
                'id' => 2,
                'name' => 'Netral',
            ],
            [
                'id' => 3,
                'name' => 'Baik',
            ],
            [
                'id' => 4,
                'name' => 'Bagus',
            ],
        ]);
    }
}
