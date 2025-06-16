<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed required tables first
        $this->call([
            DepartmentSeeder::class,
            SubjectSeeder::class,
            GradeSeeder::class,
            ClassSeeder::class,
            ClassSubjectSeeder::class,
            UserSeeder::class,
            HodSeeder::class,
            //StudentSubjectSeeder::class,
            TeacherSeeder::class,
            //StudentSeeder::class,
            PivotTableSeeder::class,
            StudentClassSeeder::class,
            EvaluationSeeder::class,
        ]);

        // Create test user only if subjects and departments exist
        if (Subject::count() > 0 && Department::count() > 0) {
            User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'subject_id' => Subject::first()->id,
                'department_id' => Department::first()->id,
            ]);
        }
    }
}