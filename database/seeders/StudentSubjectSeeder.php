<?php

namespace Database\Seeders;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSubjectSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('student_subject')->truncate();

        // Sample data - adjust based on your actual students/classes/subjects
        $assignments = [
            [
                'student_id' => 1,  // Must match a student ID
                'class_id' => 1,    // Must match a class ID
                'subject_id' => 1,  // Must match a subject ID
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'student_id' => 1,
                'class_id' => 1,
                'subject_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Add more assignments as needed
        ];

        DB::table('student_subject')->insert($assignments);
    }
}