<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'brayan',
            'email' => 'brayanhornamartinez@gmail.com',
            'password' => bcrypt('2048001041')
        ]);

        Category::factory()->count(10)->create();

        Brand::factory()->count(10)->create();
    }
}
