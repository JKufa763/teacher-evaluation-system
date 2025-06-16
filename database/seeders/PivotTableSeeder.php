<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Class_, Teacher, Subject};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

class PivotTableSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('teacher_class') || !Schema::hasTable('classes')) {
            $this->command->error('Required tables not found! Run migrations first.');
            return;
        }

        $this->seedTeacherClassRelationships();
    }

    protected function seedTeacherClassRelationships(): void
    {
        $classIds = Class_::pluck('id')->toArray();
        $teacherIds = Teacher::pluck('id')->toArray();
        $subjects = Subject::all();

        $this->command->info('Class IDs: ' . implode(', ', $classIds));
        $this->command->info('Teacher IDs: ' . implode(', ', $teacherIds));

        if (empty($classIds) || empty($teacherIds) || $subjects->isEmpty()) {
            $this->command->warn('Skipping teacher-class: No classes, teachers or subjects found');
            return;
        }

        $this->command->info('Seeding teacher-class relationships...');
        
        // Disable foreign key checks to allow truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('teacher_class')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $data = [];
        foreach ($teacherIds as $teacherId) {
            $numClasses = min(3, count($classIds));
            $selectedClassIds = Arr::random($classIds, rand(1, $numClasses));

            foreach ((array)$selectedClassIds as $classId) {
                $data[] = [
                    'teacher_id' => $teacherId,
                    'class_id' => $classId,
                    'subject_id' => $subjects->random()->id, // Add random subject
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Insert in chunks to avoid huge queries
        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('teacher_class')->insert($chunk);
        }

        $this->command->info("Created " . count($data) . " teacher-class relationships");
    }
}