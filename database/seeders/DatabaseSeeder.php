<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'sembako', 'slug' => 'sembako', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kebutuhan rumah', 'slug' => 'kebutuhan-rumah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'pakaian', 'slug' => 'pakaian', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->call(ProductSeeder::class);

        User::factory()->create([
            'name' => 'Admin Libellis',
            'email' => 'admin@libellis-shop.test',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);
    }
}
