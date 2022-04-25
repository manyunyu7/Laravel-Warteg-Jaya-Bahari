<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categories')->insert([
            [
                'id' => 1,
                'name' => 'Snack',
            ],
            [
                'id' => 2,
                'name' => 'Ice Cream',
            ],
            [
                'id' => 3,
                'name' => 'Beverage',
            ],
            [
                'id' => 4,
                'name' => 'Instant',
            ],
            [
                'id' => 5,
                'name' => 'Frozen Food',
            ],
            [
                'id' => 6,
                'name' => 'Cereal',
            ],
            [
                'id' => 7,
                'name' => 'Canned Food',
            ],
            [
                'id' => 8,
                'name' => 'Sauce/Spice',
            ],
            [
                'id' => 9,
                'name' => 'Seafood',
            ],
            [
                'id' => 10,
                'name' => 'Milk',
            ],
            [
                'id' => 11,
                'name' => 'Kids',
            ],
            [
                'id' => 12,
                'name' => 'Bread',
            ]
        ]);
    }
}
