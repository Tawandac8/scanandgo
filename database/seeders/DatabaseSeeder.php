<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Create roles and permissions
        $this->call([
            CreateUserSeeder::class,
            CreateCity::class,
            CreateCityA::class,
            CreateCityB::class,
            CreateCityCDE::class,
            CreateCityFG::class,
            CreateCityHtoM::class,
            CreateBadgeTypes::class
        ]);
    }
}
