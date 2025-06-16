<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;

class HodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['Mathematics', 'Sciences', 'Languages', 'Humanities'];

        foreach ($departments as $department) {
            $subject = Subject::where('name', $department)->first();

            if ($subject) {
                $hodUser = User::where('email',strtolower($department) . '@example.com')->first();

                if ($hodUser) {
                    $hodUser->subject_id = $subject->id;
                    $hodUser->save();
                }
            }
        }
    }
}
