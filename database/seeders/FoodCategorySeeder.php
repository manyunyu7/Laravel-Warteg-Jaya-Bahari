<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('food_categories')->insert([
            [
                'id' => 1,
                'name' => 'Halal',
            ],
            [
                'id' => 2,
                'name' => 'Non Halal',
            ],
            [
                'id' => 3,
                'name' => 'Alcohol',
            ]
        ]);
    }
}
