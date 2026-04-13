<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user admin default
        \App\Models\User::updateOrCreate(
            ['email' => 'suadmin@example.tc'],
            [
                'name'     => 'Administrator',
                'email'    => 'suadmin@example.tc',
                'password' => bcrypt('password123'),
                'role'     => 'admin',
            ]
        );
        $this->call([
            ActivityTypeSeeder::class,
            ChecklistTemplateSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
