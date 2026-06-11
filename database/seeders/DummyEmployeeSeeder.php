<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['Computer Science', 'Biology', 'IT Services', 'Administration'];
        $positions = ['Lecturer', 'Professor', 'Staff', 'Administrator'];
        $statuses = ['active', 'inactive'];
        $visibilities = ['public', 'hidden'];

        $employees = [];
        for ($i = 1; $i <= 100; $i++) {
            $employees[] = [
                'nip' => '100' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Dummy Employee ' . $i,
                'email' => 'employee' . $i . '@example.com',
                'phone' => '0812' . rand(10000000, 99999999),
                'position' => $positions[array_rand($positions)],
                'department' => $departments[array_rand($departments)],
                'status' => $statuses[array_rand($statuses)],
                'visibility' => $visibilities[array_rand($visibilities)],
                'unique_code' => rand(10000, 99999),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('employees')->insert($employees);
    }
}

