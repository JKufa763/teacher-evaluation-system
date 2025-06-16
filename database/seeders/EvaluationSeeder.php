<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Evaluation;
use App\Models\Teacher;
use App\Models\Class_;
use App\Models\User;
use App\Models\Department;
use App\Models\Hod;
use App\Models\Grade;

class EvaluationSeeder extends Seeder {
    public function run() {
        \Log::info('EvaluationSeeder started');
        Evaluation::truncate();
        \Log::info('Evaluations table truncated');

        // Student Evaluations (Directly Approved)
        $students = Student::with(['subjects.teachers', 'classes'])->get();
        \Log::info('Found ' . $students->count() . ' students');

        foreach ($students as $student) {
            $classIds = $student->classes->pluck('id');
            if ($classIds->isEmpty()) {
                \Log::info('Skipping student ID ' . $student->id . ' due to no classes');
                continue;
            }

            $classId = $classIds->first();
            \Log::info('Student ID ' . $student->id . ' has ' . $student->subjects->count() . ' subjects');
            foreach ($student->subjects as $subject) {
                $teacher = $subject->teachers->first();
                if ($teacher) {
                    Evaluation::create([
                        'student_id' => $student->id,
                        'evaluator_teacher_id' => null,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'class_id' => $classId,
                        'evaluation_type' => 'student',
                        'knowledge_rating' => null,
                        'teaching_skill' => null,
                        'communication' => null,
                        'punctuality' => null,
                        'comments' => null,
                        'user_id' => $student->user_id,
                        'completed' => false,
                        'status' => 'approved',
                        'created_at' => now()->subMonths(rand(1, 12)),
                        'updated_at' => now()->subMonths(rand(1, 12)),
                    ]);
                    \Log::info('Created student evaluation for student ID ' . $student->id . ', teacher ID ' . $teacher->id);
                }
            }
        }

        // Peer and Self Evaluations
        $teachers = Teacher::with(['subject'])->get();
        \Log::info('Found ' . $teachers->count() . ' teachers');

        foreach ($teachers as $teacher) {
            // Peer Evaluation (Directly Approved)
            $peerTeachers = $teachers->where('id', '!=', $teacher->id);
            if ($peerTeachers->isEmpty()) {
                \Log::info('No peer teachers available for teacher ID ' . $teacher->id);
                continue;
            }

            $teacherToEvaluate = $peerTeachers->random();
            $subject = $teacherToEvaluate->subject;
            if (!$subject) {
                \Log::info('Teacher ID ' . $teacherToEvaluate->id . ' has no subject assigned');
                continue;
            }

            $class = Class_::whereHas('availableSubjects', function ($query) use ($subject) {
                $query->where('subjects.id', $subject->id);
            })->first();
            if (!$class) {
                \Log::info('No class found for subject ID ' . $subject->id);
                continue;
            }

            Evaluation::create([
                'student_id' => null,
                'evaluator_teacher_id' => $teacher->id,
                'teacher_id' => $teacherToEvaluate->id,
                'subject_id' => $subject->id,
                'class_id' => $class->id,
                'evaluation_type' => 'peer',
                'knowledge_rating' => null,
                'teaching_skill' => null,
                'communication' => null,
                'punctuality' => null,
                'comments' => null,
                'user_id' => $teacher->user_id,
                'completed' => false,
                'status' => 'approved',
                'created_at' => now()->subMonths(rand(1, 12)),
                'updated_at' => now()->subMonths(rand(1, 12)),
            ]);
            \Log::info('Created peer evaluation for evaluator teacher ID ' . $teacher->id . ', teacher ID ' . $teacherToEvaluate->id);

            // Self-Evaluation (Pending Review)
            $subject = $teacher->subject;
            if (!$subject) {
                \Log::info('Teacher ID ' . $teacher->id . ' has no subject assigned');
                continue;
            }

            $class = Class_::whereHas('availableSubjects', function ($query) use ($subject) {
                $query->where('subjects.id', $subject->id);
            })->first();
            if (!$class) {
                \Log::info('No class found for subject ID ' . $subject->id);
                continue;
            }

            Evaluation::create([
                'student_id' => null,
                'evaluator_teacher_id' => $teacher->id,
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'class_id' => $class->id,
                'evaluation_type' => 'self',
                'knowledge_rating' => null,
                'teaching_skill' => null,
                'communication' => null,
                'punctuality' => null,
                'comments' => null,
                'user_id' => $teacher->user_id,
                'completed' => false,
                'status' => 'pending',
                'created_at' => now()->subMonths(rand(1, 12)),
                'updated_at' => now()->subMonths(rand(1, 12)),
            ]);
            \Log::info('Created self-evaluation for teacher ID ' . $teacher->id);
        }

        \Log::info('EvaluationSeeder completed. Total evaluations: ' . Evaluation::count());
    }
}