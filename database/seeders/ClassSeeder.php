<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Class_;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            ['name' => 'Grade 10 A', 'grade_id' => 1],
            ['name' => 'Grade 11 A', 'grade_id' => 2],
            ['name' => 'Grade 11 B', 'grade_id' => 2],
        ];

        foreach ($classes as $class) {
            // Check if the class already exists
            DB::table('classes')->updateOrInsert(
                ['name' => $class['name'], 'grade_id' => $class['grade_id']],
                $class
            );
        }
    }
}