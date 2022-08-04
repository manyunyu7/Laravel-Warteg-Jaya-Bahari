<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            [
                'id' => 1,
                'roles' => 'Admin',
            ],
            [
                'id' => 2,
                'roles' => 'Customer',
            ],
            [
                'id' => 3,
                'roles' => 'Restoran',
            ],
            [
                'id' => 4,
                'roles' => 'Driver'
            ]
        ]);
    }
}
