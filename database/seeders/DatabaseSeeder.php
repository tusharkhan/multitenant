<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Flat;
use App\Models\Tenant;
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
        $this->call([
            AdminSeeder::class,
        ]);

        Tenant::factory(10)->create();
        Building::factory(5)->create();
        Flat::factory(20)->create();
    }
}
