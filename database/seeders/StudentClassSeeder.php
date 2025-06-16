<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Class_;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentClassSeeder extends Seeder
{
    public function run()
    {
        // Clear existing relationships
        DB::table('student_class')->truncate();

        // Get all students and classes
        $students = Student::all();
        $classes = Class_::all(); // Get all classes

        if ($classes->isEmpty() || $students->isEmpty()) {
            $this->command->error('Cannot seed student_class - no students or classes found!');
            return;
        }

        $this->command->info('Seeding student-class relationships (one class per student)...');

        // Track assigned students to avoid duplication
        $assignedStudents = [];

        foreach ($students as $student) {
            // Ensure the student hasn't already been assigned a class
            if (!in_array($student->id, $assignedStudents)) {
                // Assign each student to exactly one random class
                $class = $classes->random();

                // Insert into student_class table
                DB::table('student_class')->insert([
                    'student_id' => $student->id, // Use user_id to reference students correctly
                    'class_id' => $class->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Mark this student as assigned
                $assignedStudents[] = $student->id;

                $this->command->info(sprintf(
                    'Assigned student %s to class: %s',
                    $student->user->name,
                    $class->name
                ));
            }
        }
    }
}