<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApprovalRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Employees
        $alan = \App\Models\Employee::create([
            'name' => 'Dr. Alan Turing',
            'email' => 'aturing@uiidalwa.edu',
            'nip' => '894302',
            'position' => 'Associate Professor',
            'department' => 'Dept. of Computer Science',
            'avatar' => 'https://i.pravatar.cc/150?u=alan',
            'visibility' => 'public',
        ]);

        $emma = \App\Models\Employee::create([
            'name' => 'Emma Hamilton',
            'email' => 'ehamilton@uiidalwa.edu',
            'nip' => '123456',
            'position' => 'Research Fellow',
            'department' => 'Research Dept.',
            'visibility' => 'public',
        ]);

        $sarah = \App\Models\Employee::create([
            'name' => 'Dr. Sarah Chen',
            'email' => 'schen@uiidalwa.edu',
            'nip' => '654321',
            'position' => 'Bio-Engineer',
            'department' => 'Bio-Engineering',
            'avatar' => 'https://i.pravatar.cc/150?u=sarah',
            'visibility' => 'public',
        ]);

        // Create Approval Requests
        \App\Models\ApprovalRequest::create([
            'employee_id' => $alan->id,
            'type' => 'data_change',
            'subtype' => 'Title Update',
            'description' => 'Update title to Full Professor',
            'current_data' => [
                'jobTitle' => 'Associate Professor',
                'department' => 'Dept. of Computer Science'
            ],
            'requested_data' => [
                'jobTitle' => 'Full Professor, Chair of Computing',
                'department' => 'Dept. of Computer Science'
            ],
            'faculty_comment' => 'Recently promoted by the board effective Sept 1st. Attached tenure documentation to HR system.',
            'status' => 'pending',
            'created_at' => now()->startOfDay()->addHours(9)->addMinutes(41),
        ]);

        \App\Models\ApprovalRequest::create([
            'employee_id' => $emma->id,
            'type' => 'visibility',
            'subtype' => 'Visibility',
            'description' => 'Request to hide profile from public directory.',
            'status' => 'pending',
            'created_at' => now()->subDay(),
        ]);

        \App\Models\ApprovalRequest::create([
            'employee_id' => $sarah->id,
            'type' => 'data_change',
            'subtype' => 'Data Update',
            'description' => 'Update office hours & location',
            'current_data' => [
                'officeHours' => 'Mon-Wed 10am - 12pm',
                'location' => 'Building A, Room 101'
            ],
            'requested_data' => [
                'officeHours' => 'Tue-Thu 1pm - 3pm',
                'location' => 'Building B, Room 205'
            ],
            'status' => 'pending',
            'created_at' => now()->subDays(5), // Approximate Oct 12
        ]);
        
        // Processed requests
        \App\Models\ApprovalRequest::create([
            'employee_id' => $alan->id, // Just reusing $alan for mock
            'type' => 'data_change',
            'subtype' => 'Bio Update',
            'description' => 'M. Davis (Bio Update)',
            'status' => 'approved',
            'created_at' => now()->subHours(2),
        ]);

        \App\Models\ApprovalRequest::create([
            'employee_id' => $emma->id, // Reusing
            'type' => 'data_change',
            'subtype' => 'Photo Upload',
            'description' => 'J. Smith (Photo Upload)',
            'status' => 'rejected',
            'created_at' => now()->subDay(),
        ]);
    }
}
