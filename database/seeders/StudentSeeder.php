<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\Class_;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentUsers = User::where('role', 'student')->get();
        $classes = Class_::all();

        foreach ($studentUsers as $studentUser) {
            $student = Student::create([
                'user_id' => $studentUser->id,
                'class_id' => $classes->random()->id,
            ]);
        }
    }
}
