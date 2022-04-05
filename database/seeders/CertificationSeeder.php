<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('certifications')->insert([
            [
                'id' => 1,
                'name' => 'Halal Certified',
            ],
            [
                'id' => 2,
                'name' => 'Muslim Friendly',
            ],
            [
                'id' => 3,
                'name' => 'Self Certified',
            ],
            [
                'id' => 4,
                'name' => 'Pork Free',
            ]
        ]);
    }
}
