<?php

namespace App\Http\Controllers;

use App\Models\Class_;
use App\Models\Evaluation;
use App\Models\EvaluationCycle;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EvaluationController extends Controller
{
    public function showForm($evaluationId)
    {
        $evaluation = Evaluation::with(['subject', 'teacher.user', 'teacher.department'])->findOrFail($evaluationId);
        if ($evaluation->student_id !== Auth::user()->student->id) {
            Log::warning('Unauthorized access to evaluation form', [
                'student_id' => Auth::user()->student->id,
                'evaluation_id' => $evaluationId,
            ]);
            abort(403, 'Unauthorized');
        }
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        if (!$currentCycle) {
            return redirect()->route('student.dashboard')->with('error', 'No active evaluation cycle.');
        }
        return view('evaluations.create', [
            'subject' => $evaluation->subject,
            'evaluation' => $evaluation,
            'teacher' => $evaluation->teacher, // Pass the teacher directly
            'currentCycle' => $currentCycle,
        ]);
    }

    public function index()
    {
        $evaluations = Evaluation::with(['teacher.user', 'subject', 'class'])
            ->where('student_id', Auth::user()->student->id)
            ->where('completed', false)
            ->get();
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        return view('evaluations.index', compact('evaluations', 'currentCycle'));
    }

    public function edit(Evaluation $evaluation)
    {
        if ($evaluation->student_id !== Auth::user()->student->id) {
            Log::warning('Unauthorized edit attempt', [
                'student_id' => Auth::user()->student->id,
                'evaluation_id' => $evaluation->id,
            ]);
            abort(403, 'Unauthorized');
        }
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        return view('evaluations.edit', compact('evaluation', 'currentCycle'));
    }

    public function store(Request $request)
    {
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        if (!$currentCycle) {
            Log::warning('No active evaluation cycle', ['user_id' => Auth::user()->id]);
            return redirect()->back()->with('error', 'No active evaluation cycle.');
        }

        $validated = $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'subject_id' => 'required|exists:subjects,id',
            'knowledge_rating' => 'required|integer|min:1|max:5',
            'teaching_skill' => 'required|integer|min:1|max:5',
            'communication' => 'required|integer|min:1|max:5',
            'punctuality' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        $evaluation = Evaluation::findOrFail($validated['evaluation_id']);
        if ($evaluation->student_id !== Auth::user()->student->id) {
            Log::warning('Unauthorized evaluation submission', [
                'student_id' => Auth::user()->student->id,
                'evaluation_id' => $evaluation->id,
            ]);
            abort(403, 'Unauthorized');
        }

        if ($evaluation->completed) {
            Log::warning('Evaluation already completed', ['evaluation_id' => $evaluation->id]);
            return redirect()->back()->with('error', 'Evaluation already completed.');
        }

        try {
            DB::beginTransaction();

            $evaluation->update([
                'knowledge_rating' => $validated['knowledge_rating'],
                'teaching_skill' => $validated['teaching_skill'],
                'communication' => $validated['communication'],
                'punctuality' => $validated['punctuality'],
                'score' => null,
                'comments' => $validated['comments'],
                'evaluation_cycle_id' => $currentCycle->id, // Changed to evaluation_cycle_id
                'completed' => true,
                'status' => 'approved',
                'updated_at' => now(),
            ]);

            // Update pivot record
            DB::table('student_subject')
                ->where('student_id', Auth::user()->student->id)
                ->where('subject_id', $validated['subject_id'])
                ->update([
                    'completed' => true,
                    'updated_at' => now(),
                ]);

            DB::commit();
            Log::info('Student evaluation submitted', [
                'evaluation_id' => $evaluation->id,
                'student_id' => Auth::user()->student->id,
                'ratings' => $validated,
            ]);
            return redirect()->route('student.dashboard')->with('success', 'Evaluation submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student evaluation submission failed', [
                'evaluation_id' => $validated['evaluation_id'],
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to submit evaluation: ' . $e->getMessage());
        }
    }
}