<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSubjectSeeder extends Seeder
{
    public function run()
    {
        // Fetch the class IDs after seeding
        $classes = [
            1, // Grade 10 A
            2, // Grade 11 A
            3  // Grade 11 B
        ];

        $classSubjects = [
            // Class 1 subjects
            ['class_id' => $classes[0], 'subject_id' => 1],
            ['class_id' => $classes[0], 'subject_id' => 2],
            ['class_id' => $classes[0], 'subject_id' => 3],
            ['class_id' => $classes[0], 'subject_id' => 4],
            ['class_id' => $classes[0], 'subject_id' => 5],

            

            // Class 2 subjects
            ['class_id' => $classes[1], 'subject_id' => 2],
            ['class_id' => $classes[1], 'subject_id' => 3],
            ['class_id' => $classes[1], 'subject_id' => 4],
            ['class_id' => $classes[1], 'subject_id' => 1],
            ['class_id' => $classes[1], 'subject_id' => 5],


            // Class 3 subjects
            ['class_id' => $classes[2], 'subject_id' => 3],
            ['class_id' => $classes[2], 'subject_id' => 4],
            ['class_id' => $classes[2], 'subject_id' => 5],
            ['class_id' => $classes[2], 'subject_id' => 2],
            ['class_id' => $classes[2], 'subject_id' => 1],

        ];

        DB::table('class_subject')->insert($classSubjects);
    }
}