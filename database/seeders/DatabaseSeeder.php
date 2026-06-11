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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        \App\Models\Employee::create([
            'name' => 'Dr. Sarah Jenkins',
            'email' => 's.jenkins@university.edu',
            'nip' => '104859302',
            'position' => 'Associate Professor',
            'department' => 'Computer Science',
            'status' => 'active',
            'visibility' => 'public',
        ]);

        \App\Models\Employee::create([
            'name' => 'Michael Chang',
            'email' => 'm.chang@university.edu',
            'nip' => '104859315',
            'position' => 'System Administrator',
            'department' => 'IT Services',
            'status' => 'active',
            'visibility' => 'hidden',
        ]);
    }
}
