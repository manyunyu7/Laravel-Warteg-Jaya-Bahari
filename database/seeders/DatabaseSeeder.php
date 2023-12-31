<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            RatingSeeder::class,
            CertificationSeeder::class,
            MasjidTypeSeeder::class,
            ProductCategorySeeder::class,
            ForumCategorySeeder::class,
            TypeFoodSeeder::class,
            OrderStatusSeeder::class,
        ]);
    }
}
