<?php

namespace App\Http\Controllers;

use App\Models\Class_;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Evaluation;
use App\Models\EvaluationCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            Log::warning('Unauthorized access: User is not a teacher', ['user_id' => Auth::user()->id]);
            abort(403, 'Unauthorized access: User is not a teacher');
        }
        $classes = $teacher->classes;
        $recentEvaluations = Evaluation::where('teacher_id', $teacher->id)
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get();
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        return view('teacher.dashboard', compact('classes', 'recentEvaluations', 'currentCycle'));
    }

    public function selfEvaluate()
    {
        $teacher = Auth::user()->teacher;
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        if (!$currentCycle) {
            Log::warning('No active evaluation cycle', ['user_id' => Auth::user()->id]);
            return redirect()->route('teacher.dashboard')->with('error', 'No active evaluation cycle. Contact admin.');
        }
        $evaluation = Evaluation::where('teacher_id', $teacher->id)
            ->where('evaluation_type', 'self')
            ->where('evaluation_cycle_id', $currentCycle->id)
            ->first();

        return view('teacher.self-evaluate', compact('evaluation', 'currentCycle', 'teacher'));
    }

    public function storeSelfEvaluation(Request $request)
    {
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        if (!$currentCycle) {
            Log::warning('No active evaluation cycle', ['user_id' => Auth::user()->id]);
            return redirect()->back()->with('error', 'No active evaluation cycle. Contact admin.');
        }

        $teacher = Auth::user()->teacher;
        if (!$teacher || !$teacher->subject_id) {
            Log::error('Teacher or subject not found', ['user_id' => Auth::user()->id]);
            return redirect()->back()->with('error', 'No subject assigned to this teacher. Please contact the administrator.');
        }

        $existingEvaluation = Evaluation::where('teacher_id', $teacher->id)
            ->where('evaluation_type', 'self')
            ->where('evaluation_cycle_id', $currentCycle->id)
            ->first();

        if ($existingEvaluation && $existingEvaluation->status !== 'rejected') {
            Log::warning('Duplicate self-evaluation attempt', ['teacher_id' => $teacher->id]);
            return redirect()->route('teacher.self-evaluate')->with('error', 'Self-evaluation already submitted for this cycle. Awaiting HOD review or approved.');
        }

        $validated = $request->validate([
            'core_competencies' => 'required|array',
            'core_competencies.knowledge_rating' => 'required|integer|min:0|max:5',
            'core_competencies.teaching_skill' => 'required|integer|min:0|max:5',
            'core_competencies.communication' => 'required|integer|min:0|max:5',
            'core_competencies.punctuality' => 'required|integer|min:0|max:5',
            'performance_planning' => 'required|array',
            'performance_planning.*.actual_performance' => 'required|numeric|min:0',
            'performance_planning.*.score' => 'required|numeric|min:0|max:10',
            'performance_planning.*.evidence' => 'required|string|max:1000',
            'personal_attributes' => 'required|array',
            'personal_attributes.*' => 'required|in:excellent,very_good,satisfactory,requires_improvement,unsatisfactory',
        ]);

        $performancePlanning = [
            'lessons_delivered' => [
                'target' => 500,
                'actual_performance' => $validated['performance_planning']['lessons_delivered']['actual_performance'],
                'score' => $validated['performance_planning']['lessons_delivered']['score'],
                'evidence' => $validated['performance_planning']['lessons_delivered']['evidence'],
            ],
            'exercises_tests' => [
                'target' => 700,
                'actual_performance' => $validated['performance_planning']['exercises_tests']['actual_performance'],
                'score' => $validated['performance_planning']['exercises_tests']['score'],
                'evidence' => $validated['performance_planning']['exercises_tests']['evidence'],
            ],
            'records_maintained' => [
                'target' => 8,
                'actual_performance' => $validated['performance_planning']['records_maintained']['actual_performance'],
                'score' => $validated['performance_planning']['records_maintained']['score'],
                'evidence' => $validated['performance_planning']['records_maintained']['evidence'],
            ],
            'sporting_sessions' => [
                'target' => 20,
                'actual_performance' => $validated['performance_planning']['sporting_sessions']['actual_performance'],
                'score' => $validated['performance_planning']['sporting_sessions']['score'],
                'evidence' => $validated['performance_planning']['sporting_sessions']['evidence'],
            ],
        ];

        $performanceScores = collect($validated['performance_planning'])->pluck('score')->map(fn($score) => (int)$score);
        $totalPerformanceScore = $performanceScores->sum();

        try {
            DB::beginTransaction();

            if ($existingEvaluation && $existingEvaluation->status === 'rejected') {
                $existingEvaluation->update([
                    'knowledge_rating' => $validated['core_competencies']['knowledge_rating'],
                    'teaching_skill' => $validated['core_competencies']['teaching_skill'],
                    'communication' => $validated['core_competencies']['communication'],
                    'punctuality' => $validated['core_competencies']['punctuality'],
                    'score' => $totalPerformanceScore,
                    'comments' => json_encode([
                        'performance_planning' => $performancePlanning,
                        'personal_attributes' => $validated['personal_attributes'],
                    ], JSON_THROW_ON_ERROR),
                    'status' => 'pending',
                    'hod_comments' => null,
                    'evaluation_cycle_id' => $currentCycle->id, // Added to ensure consistency
                    'updated_at' => now(),
                ]);
            } else {
                Evaluation::create([
                    'teacher_id' => $teacher->id,
                    'user_id' => Auth::user()->id,
                    'evaluator_teacher_id' => $teacher->id,
                    'subject_id' => $teacher->subject_id,
                    'evaluation_type' => 'self',
                    'status' => 'pending',
                    'evaluation_cycle_id' => $currentCycle->id, // Changed to evaluation_cycle_id
                    'knowledge_rating' => $validated['core_competencies']['knowledge_rating'],
                    'teaching_skill' => $validated['core_competencies']['teaching_skill'],
                    'communication' => $validated['core_competencies']['communication'],
                    'punctuality' => $validated['core_competencies']['punctuality'],
                    'score' => $totalPerformanceScore,
                    'comments' => json_encode([
                        'performance_planning' => $performancePlanning,
                        'personal_attributes' => $validated['personal_attributes'],
                    ], JSON_THROW_ON_ERROR),
                    'completed' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            Log::info('Self-evaluation submitted', [
                'teacher_id' => $teacher->id,
                'cycle_id' => $currentCycle->id,
                'score' => $totalPerformanceScore,
            ]);
            return redirect()->route('teacher.self-evaluate')->with('success', 'Self-evaluation submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Self-evaluation submission failed', [
                'teacher_id' => $teacher->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to submit self-evaluation: ' . $e->getMessage());
        }
    }

    public function peerEvaluate()
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found.');
        }
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        if (!$currentCycle) {
            return redirect()->route('teacher.dashboard')->with('error', 'No active evaluation cycle.');
        }

        $evaluatedTeacherIds = Evaluation::where('evaluator_teacher_id', $teacher->id)
            ->where('evaluation_type', 'peer')
            ->where('evaluation_cycle_id', $currentCycle->id)
            ->pluck('teacher_id')
            ->toArray();

        $peers = Teacher::where('id', '!=', $teacher->id)
            ->whereNotIn('id', $evaluatedTeacherIds)
            ->with(['user', 'department'])
            ->get();

        return view('teacher.peer-evaluate', compact('peers', 'currentCycle'));
    }

    public function storePeerEvaluation(Request $request)
    {
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        if (!$currentCycle) {
            Log::warning('No active evaluation cycle', ['user_id' => Auth::user()->id]);
            return redirect()->back()->with('error', 'No active evaluation cycle. Contact admin.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'knowledge_rating' => 'required|integer|min:1|max:5',
            'teaching_skill' => 'required|integer|min:1|max:5',
            'communication' => 'required|integer|min:1|max:5',
            'punctuality' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $teacher = Teacher::findOrFail($validated['teacher_id']);
            $evaluator = Auth::user()->teacher;

            if (!$evaluator) {
                Log::error('Evaluator teacher not found', ['user_id' => Auth::user()->id]);
                return redirect()->back()->with('error', 'Evaluator profile not found.');
            }

            if ($teacher->id === $evaluator->id) {
                Log::warning('Self-evaluation attempted in peer form', ['teacher_id' => $teacher->id]);
                return redirect()->back()->with('error', 'You cannot evaluate yourself.');
            }

            if (!$teacher->subject_id) {
                Log::error('No subject assigned to teacher', ['teacher_id' => $teacher->id]);
                return redirect()->back()->with('error', 'No subject assigned to the evaluated teacher.');
            }

            $existingEvaluation = Evaluation::where('evaluator_teacher_id', $evaluator->id)
                ->where('teacher_id', $validated['teacher_id'])
                ->where('evaluation_type', 'peer')
                ->where('evaluation_cycle_id', $currentCycle->id)
                ->first();

            if ($existingEvaluation) {
                Log::warning('Duplicate peer evaluation attempt', [
                    'evaluator_id' => $evaluator->id,
                    'teacher_id' => $validated['teacher_id'],
                ]);
                return redirect()->back()->with('error', 'You have already evaluated this teacher in this cycle.');
            }

            Evaluation::create([
                'teacher_id' => $validated['teacher_id'],
                'user_id' => Auth::user()->id,
                'evaluator_teacher_id' => $evaluator->id,
                'subject_id' => $teacher->subject_id,
                'evaluation_type' => 'peer',
                'status' => 'approved',
                'evaluation_cycle_id' => $currentCycle->id, // Changed to evaluation_cycle_id
                'knowledge_rating' => $validated['knowledge_rating'],
                'teaching_skill' => $validated['teaching_skill'],
                'communication' => $validated['communication'],
                'punctuality' => $validated['punctuality'],
                'score' => null,
                'comments' => $validated['comments'] ?? null,
                'completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            Log::info('Peer evaluation submitted', [
                'teacher_id' => $validated['teacher_id'],
                'evaluator_id' => $evaluator->id,
                'cycle_id' => $currentCycle->id,
            ]);
            return redirect()->route('teacher.dashboard')->with('success', 'Peer evaluation submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Peer evaluation failed', [
                'teacher_id' => $validated['teacher_id'] ?? null,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to submit peer evaluation: ' . $e->getMessage());
        }
    }
}

/*public function peerEvaluate() {
        if (Auth::user()->role !== 'teacher') {
            abort(403, 'Unauthorized access');
        }

        $teacher = Auth::user()->teacher;
        $peerTeachers = Teacher::where('id', '!=', $teacher->id)->with('subject')->get();
        return view('teacher.peer-evaluate', compact('peerTeachers'));
    }

    public function submitPeerEvaluation(Request $request) {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'knowledge_rating' => 'nullable|integer|min:1|max:5',
            'teaching_skill' => 'nullable|integer|min:1|max:5',
            'communication' => 'nullable|integer|min:1|max:5',
            'punctuality' => 'nullable|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ]);

        $evaluatorTeacher = Auth::user()->teacher;
        $teacherToEvaluate = Teacher::findOrFail($request->teacher_id);
        $subject = $teacherToEvaluate->subject;
        $class = Class_::whereHas('subjects', function ($query) use ($subject) {
            $query->where('subjects.id', $subject->id);
        })->first();

        if (!$class) {
            return redirect()->back()->with('error', 'No class found for the selected teacher’s subject.');
        }

        Evaluation::create([
            'student_id' => null,
            'evaluator_teacher_id' => $evaluatorTeacher->id,
            'teacher_id' => $teacherToEvaluate->id,
            'subject_id' => $subject->id,
            'class_id' => $class->id,
            'evaluation_type' => 'peer',
            'knowledge_rating' => $request->knowledge_rating,
            'teaching_skill' => $request->teaching_skill,
            'communication' => $request->communication,
            'punctuality' => $request->punctuality,
            'comments' => $request->comments,
            'user_id' => $evaluatorTeacher->user_id,
            'completed' => true,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('teacher.peer-evaluate')->with('success', 'Peer evaluation submitted successfully.');
    }*/

    /*public function evaluationReport()
    {
        $teacher = Auth::user()->teacher;
        $evaluations = Evaluation::where('teacher_id', $teacher->id)
            ->where('status', 'approved')
            ->get();

        // Separate evaluations by type
        $selfEvaluations = $evaluations->where('evaluation_type', 'self');
        $peerEvaluations = $evaluations->where('evaluation_type', 'peer');
        $studentEvaluations = $evaluations->where('evaluation_type', 'student');

        // Aggregate ratings
        $currentPerformance = [
            'knowledge_rating' => $evaluations->avg('knowledge_rating'),
            'teaching_skill' => $evaluations->avg('teaching_skill'),
            'communication' => $evaluations->avg('communication'),
            'punctuality' => $evaluations->avg('punctuality'),
        ];

        $weakAreas = array_filter($currentPerformance, fn($rating) => $rating < 3);

        $pastPerformance = Evaluation::select(
            \DB::raw('MONTH(created_at) as month'),
            \DB::raw('YEAR(created_at) as year'),
            \DB::raw('AVG(knowledge_rating) as avg_knowledge'),
            \DB::raw('AVG(teaching_skill) as avg_teaching'),
            \DB::raw('AVG(communication) as avg_communication'),
            \DB::raw('AVG(punctuality) as avg_punctuality')
        )
        ->where('teacher_id', $teacher->id)
        ->where('status', 'approved')
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'label' => date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year)),
                'avg_knowledge' => $item->avg_knowledge,
                'avg_teaching' => $item->avg_teaching,
                'avg_communication' => $item->avg_communication,
                'avg_punctuality' => $item->avg_punctuality,
            ];
        });

        return view('teacher.evaluation-report', compact('teacher', 'currentPerformance', 'weakAreas', 'pastPerformance', 'selfEvaluations', 'peerEvaluations', 'studentEvaluations'));
    }*/


