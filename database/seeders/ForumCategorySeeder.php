<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('forum_categories')->insert([
            [
                'id' => 1,
                'name' => 'Mosque',
            ],
            [
                'id' => 2,
                'name' => 'Restaurant',
            ],
            [
                'id' => 3,
                'name' => 'Ingredient',
            ],
            [
                'id' => 4,
                'name' => 'Transportation',
            ],
            [
                'id' => 5,
                'name' => 'Prayer Spot',
            ],
            [
                'id' => 6,
                'name' => 'Other',
            ],
        ]);
    }
}
