<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'Mathematics' => ['Mathematics'],
            'Sciences' => ['Physics'],
            'Languages' => ['English', 'Shona'],
            'Humanities' => ['History']
        ];
    
        foreach ($departments as $deptName => $subjects) {
            // Create department
            $department = Department::create(['name' => $deptName, 'hod_id' => null]);
    
            // Generate the email for the HOD
            $hodEmail = 'hod.' . strtolower($deptName) . '@example.com';
    
            // Check if the HOD user already exists
            $hod = User::where('username', strtolower($deptName) . '_hod')->first();
    
            if (!$hod) {
                // Create HOD user if it doesn't exist
                $hod = User::create([
                    'name' => 'HOD ' . $deptName,
                    'username' => strtolower($deptName) . '_hod',
                    'email' => $hodEmail,
                    'password' => bcrypt('password'),
                    'role' => 'hod',
                    'department_id' => $department->id,
                    'subject_id' => null
                ]);
            } else {
                // Update the existing HOD's department_id if needed
                $hod->update(['department_id' => $department->id]);
            }
    
            // Update department with HOD
            $department->update(['hod_id' => $hod->id]);
    
            // Create Teachers for Each Subject
            foreach ($subjects as $subjectName) {
                for ($i = 1; $i <= 2; $i++) {
                    $teacherUsername = strtolower(str_replace(' ', '_', $subjectName)) . '_teacher_' . $i;
                    $teacherEmail = strtolower(str_replace(' ', '_', $subjectName)) . '.teacher' . $i . '@example.com';
    
                    // Check if the teacher already exists
                    if (!User::where('username', $teacherUsername)->exists() && !User::where('email', $teacherEmail)->exists()) {
                        User::create([
                            'name' => $subjectName . ' Teacher ' . $i,
                            'username' => $teacherUsername,
                            'email' => $teacherEmail,
                            'password' => bcrypt('password'),
                            'role' => 'teacher',
                            'department_id' => $department->id,
                            'subject_id' => null // Set this after subjects exist
                        ]);
                    }
                }
            }
        }
    }
}