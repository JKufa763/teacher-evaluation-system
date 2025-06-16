<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'department_id' => 1], // Mathematics -> Department 1
            ['name' => 'Physics', 'department_id' => 2],    // Physics -> Department 2
            ['name' => 'English', 'department_id' => 3],    // English -> Department 3
            ['name' => 'Shona', 'department_id' => 3],      // Shona -> Department 3
            ['name' => 'History', 'department_id' => 4],     // History -> Department 4
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['name' => $subject['name'], 'department_id' => $subject['department_id']],
                ['name' => $subject['name'], 'department_id' => $subject['department_id']]
            );
        }
    }
}