<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeFoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_food')->insert([
            [
                'id' => 1,
                'name' => 'Asian',
            ],
            [
                'id' => 2,
                'name' => 'Arabic',
            ],
            [
                'id' => 3,
                'name' => 'Chinese',
            ],
            [
                'id' => 4,
                'name' => 'Indonesian',
            ],
            [
                'id' => 5,
                'name' => 'Indian',
            ],
            [
                'id' => 6,
                'name' => 'Japanese',
            ],
            [
                'id' => 7,
                'name' => 'Korean',
            ],
            [
                'id' => 8,
                'name' => 'Turkish',
            ],
            [
                'id' => 9,
                'name' => 'Western',
            ],
        ]);
    }
}
