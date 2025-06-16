<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        //Diable foreign key checks to avoid constraint violations
        Schema::disableForeignKeyConstraints();

        // Clear existing teacher records
        DB::table('teachers')->truncate();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $teacherUsers = User::where('role', 'teacher')->get();
        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            $this->command->error('No subjects found! Please seed subjects first.');
            return;
        }

        // Ensure we have enough subjects for all teachers
        if ($teacherUsers->count() > $subjects->count()) {
            $this->command->warn('Warning: More teachers than subjects. Some subjects will be reused.');
        }

        $subjectIndex = 0;
        $subjectCount = $subjects->count();

        foreach ($teacherUsers as $teacherUser) {
            // Get the next subject (cycle through if needed)
            $subject = $subjects[$subjectIndex % $subjectCount];
            
            // Create the teacher with their subject
            Teacher::create([
                'user_id' => $teacherUser->id,
                'subject_id' => $subject->id
            ]);

            $this->command->info(sprintf(
                'Assigned teacher %s to subject: %s',
                $teacherUser->name,
                $subject->name
            ));

            $subjectIndex++;
        }
    }
}