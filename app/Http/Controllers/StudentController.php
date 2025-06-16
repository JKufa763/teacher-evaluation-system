<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Class_;
use App\Models\Evaluation;
use App\Models\EvaluationCycle;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EvaluationController;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user()->student;
        $currentCycle = EvaluationCycle::where('is_active', true)->first();

        if (!$currentCycle) {
            return view('student.dashboard', [
                'pendingEvaluations' => collect(),
                'completedEvaluations' => collect(),
                'subjects' => collect(),
                'currentCycle' => null
            ])->with('error', 'No active evaluation cycle found.');
        }

        // Get all subjects assigned to the student through student_subject table
        $subjects = $student->subjects()
            ->with(['evaluations' => function($query) use ($currentCycle, $student) {
                $query->where('evaluation_cycle_id', $currentCycle->id)
                      ->where('student_id', $student->id);
            }])
            ->get()
            ->map(function($subject) use ($currentCycle, $student) {
                // Check if there's a completed evaluation for this subject in current cycle
                $hasCompletedEvaluation = $subject->evaluations
                    ->where('evaluation_cycle_id', $currentCycle->id)
                    ->where('student_id', $student->id)
                    ->where('completed', true)
                    ->isNotEmpty();
                
                // Update the pivot completed status
                $subject->pivot->completed = $hasCompletedEvaluation;
                return $subject;
            });

        // Get pending evaluations for the current cycle
        $pendingEvaluations = Evaluation::with(['teacher.user', 'subject', 'class'])
            ->where('student_id', $student->id)
            ->where('evaluation_cycle_id', $currentCycle->id)
            ->where('completed', false)
            ->get();

        // Get completed evaluations for the current cycle
        $completedEvaluations = Evaluation::with(['teacher.user', 'subject', 'class'])
            ->where('student_id', $student->id)
            ->where('evaluation_cycle_id', $currentCycle->id)
            ->where('completed', true)
            ->get();

        return view('student.dashboard', [
            'pendingEvaluations' => $pendingEvaluations,
            'completedEvaluations' => $completedEvaluations,
            'subjects' => $subjects,
            'currentCycle' => $currentCycle
        ]);
    }
}
