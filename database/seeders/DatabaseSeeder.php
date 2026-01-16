<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Student;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Rooms
        $rooms = [
            [
                'room_number' => '101',
                'type' => 'single',
                'capacity' => 1,
                'price_per_academic_year' => 2400.00,
                'description' => 'Single room with attached bathroom',
                'status' => 'available',
                'features' => ['wifi', 'ac', 'attached_bathroom']
            ],
            [
                'room_number' => '102',
                'type' => 'single',
                'capacity' => 1,
                'price_per_academic_year' => 2400.00,
                'description' => 'Single room with shared bathroom',
                'status' => 'occupied',
                'features' => ['wifi', 'fan']
            ],
            [
                'room_number' => '201',
                'type' => 'double',
                'capacity' => 2,
                'price_per_semester' => 800.00,
                'description' => 'Double sharing room',
                'status' => 'available',
                'features' => ['wifi', 'ac', 'shared_bathroom']
            ],
            [
                'room_number' => '202',
                'type' => 'double',
                'capacity' => 2,
                'price_per_semester' => 800.00,
                'description' => 'Double sharing room',
                'status' => 'maintenance',
                'features' => ['wifi', 'fan', 'shared_bathroom']
            ],
            [
                'room_number' => '301',
                'type' => 'suite',
                'capacity' => 2,
                'price_per_semester' => 1500.00,
                'description' => 'Premium suite room',
                'status' => 'available',
                'features' => ['wifi', 'ac', 'attached_bathroom', 'tv', 'fridge']
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::create($roomData);
        }

        // Create Students
        $students = [
            [
                'student_id' => 'STU001',
                'name' => 'John Smith',
                'email' => 'john.smith@university.edu',
                'phone' => '+1234567890',
                'address' => '123 Main St, City, State',
                'department' => 'Computer Science',
                'course' => 'BSc Computer Science',
                'year_level' => 2,
                'emergency_contact' => 'Jane Smith',
                'emergency_phone' => '+1234567891',
                'status' => 'active'
            ],
            [
                'student_id' => 'STU002',
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@university.edu',
                'phone' => '+1234567892',
                'address' => '456 Oak Ave, City, State',
                'department' => 'Business Administration',
                'course' => 'BBA',
                'year_level' => 3,
                'emergency_contact' => 'Robert Johnson',
                'emergency_phone' => '+1234567893',
                'status' => 'active'
            ]
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        // Create Sample Booking
        Booking::create([
            'student_id' => 1,
            'room_id' => 2,
            'check_in' => now()->addDays(7),
            'check_out' => now()->addMonths(6),
            'semesters' => 1,
            'total_amount' => 1200.00,
            'status' => 'confirmed'
        ]);

        $this->command->info('Sample data seeded successfully!');
    }
}