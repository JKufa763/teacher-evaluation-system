<?php

namespace Database\Seeders;

use App\Models\{User, Subject, Department, Class_, Grade, Student};
use Illuminate\Support\Facades\{Hash, DB};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        Student::truncate();
        DB::table('student_class')->truncate();
        DB::table('student_subject')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // 1. Create System Admin
        $this->createUser([
            'name' => 'System Administrator',
            'username' => 'sysadmin',
            'email' => 'admin@school.edu',
            'password' => 'SecureAdmin@123',
            'role' => 'admin',
            'department_id' => null
        ]);

        // 2. Create Deputy Head
        $this->createUser([
            'name' => 'Deputy Head Academic',
            'username' => 'deputy_head',
            'email' => 'deputy@school.edu',
            'password' => 'DeputyHead@2024',
            'role' => 'deputy_head',
            'department_id' => null
        ]);

        // Create grades and classes first
        //$this->seedGrades();
        $classes = Class_::with('availableSubjects')->get();
        $subjects = Subject::all();

        // 3. Create Students
        $this->seedStudents(50, $classes, $subjects);

        // 4. Create Departments with HODs and Teachers
        $this->seedAcademicStructure();
    }

    protected function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'department_id' => $data['department_id'] ?? null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Create student record if role is student
        if ($data['role'] === 'student') {
            $user->student()->create();
        }

        return $user;
    }

    /*protected function seedGrades(): void
    {
        $grades = ['Grade 10', 'Grade 11'];
        foreach ($grades as $gradeName) {
            Grade::create(['name' => $gradeName]);
        }
    }*/

    /*protected function seedClasses(): \Illuminate\Database\Eloquent\Collection
    {
        $classes = [
            ['name' => 'Grade 10 A', 'grade_id' => 1],
            ['name' => 'Grade 11 A', 'grade_id' => 2],
            ['name' => 'Grade 11 B', 'grade_id' => 2]
        ];

        foreach ($classes as $class) {
            Class_::create($class);
        }

        return Class_::all();
    }*/

    protected function seedStudents(int $count, $classes, $subjects): void
    {
        // Check if we already have 50 students
        $existingStudentsCount = Student::count();
        if ($existingStudentsCount >= $count) {
            return; // Exit if we already have enough students
        }

    // Calculate how many more students we need
    $studentsToCreate = $count - $existingStudentsCount;

        for ($i = 1; $i <= $count; $i++) {
            $user = $this->createUser([
                'name' => 'Student ' . Str::padLeft($i, 3, '0'),
                'username' => 'student_' . Str::padLeft($i, 3, '0'),
                'email' => 'student' . Str::padLeft($i, 3, '0') . '@school.edu',
                'password' => 'Student@2024',
                'role' => 'student',
               // 'department_id' => $this->getRandomDepartmentId(),
            ]);

            $class = $classes->random();

            // Assign to class
            DB::table('student_class')->insert([
                'student_id' => $user->student->id,
                'class_id' => $class->id,
                //'subject_id' => $class->availableSubjects->random()->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Assign subjects (4 per student)
            $subjectAttachments = [];
            foreach ($subjects->random(4) as $subject) {
                $subjectAttachments[] = [
                    'student_id' => $user->student->id,
                    'subject_id' => $subject->id,
                    'class_id' => $class->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('student_subject')->insert($subjectAttachments);
        }
    }

    protected function seedAcademicStructure(): void
    {
        $departments = [
            'Mathematics' => ['Mathematics'],
            'Sciences' => ['Physics'],
            'Languages' => ['English', 'Shona'],
            'Humanities' => ['History'],
        ];

        foreach ($departments as $deptName => $deptSubjects) {
            $department = Department::firstOrCreate(['name' => $deptName]);

            // Create HOD
            $hod = $this->createUser([
                'name' => 'HOD ' . $deptName,
                'username' => Str::slug($deptName) . '_hod',
                'email' => Str::slug($deptName) . '.hod@school.edu',
                'password' => 'HOD' . Str::slug($deptName) . '@2025',
                'role' => 'hod',
                'department_id' => $department->id
            ]);

            $department->update(['hod_id' => $hod->id]);

            // Create teachers and subjects
            foreach ($deptSubjects as $subjectName) {
                $subject = Subject::firstOrCreate([
                    'name' => $subjectName,
                    'department_id' => $department->id
                ]);
                
                $this->createUser([
                    'name' => $subjectName . ' Teacher',
                    'username' => Str::slug($subjectName) . '_teacher',
                    'email' => Str::slug($subjectName) . '.teacher@school.edu',
                    'password' => 'Teach' . Str::slug($subjectName) . '@2024',
                    'role' => 'teacher',
                    'subject_id' => $subject->id,
                    'department_id' => $department->id
                ]);
            }
        }
    }

    /*protected function getRandomDepartmentId(): ?int
    {
        return Department::inRandomOrder()->first()?->id;
    }
*/
}