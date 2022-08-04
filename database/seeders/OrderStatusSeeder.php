<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_status')->insert([
            [
                'id' => 1,
                'name' => 'Waiting',
            ],
            [
                'id' => 2,
                'name' => 'On Cooking',
            ],
            [
                'id' => 3,
                'name' => 'On The Way',
            ],
            [
                'id' => 4,
                'name' => 'Completed',
            ],
            [
                'id' => 5,
                'name' => 'Canceled',
            ],
        ]);
    }
}
