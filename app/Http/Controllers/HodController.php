<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Class_;
use App\Models\EvaluationCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HodController extends Controller
{
    public function dashboard()
    {
        try {
            $hod = Auth::user();
            if (!$hod->hod) {
                Log::error('HOD user ID ' . $hod->id . ' has no associated Hod record.');
                return redirect()->route('dashboard')->with('error', 'No HOD profile found.');
            }

            $department = $hod->hod->department;
            if (!$department) {
                Log::error('HOD ID ' . $hod->hod->id . ' has no associated department.');
                return redirect()->route('dashboard')->with('error', 'No department assigned.');
            }

            $currentCycle = EvaluationCycle::where('is_active', true)->first();

            $stats = [
                'teacher_count' => $department->teachers()->count(),
                'subjects_count' => $department->subjects()->count(),
                'pending_evaluation' => Evaluation::whereIn('teacher_id', $department->teachers()->pluck('id'))
                    ->where('status', 'pending')
                    ->where('evaluation_type', 'self')
                    ->where('evaluation_cycle_id', $currentCycle ? $currentCycle->id : 0)
                    ->count(),
            ];

            $recentEvaluations = Evaluation::whereIn('teacher_id', $department->teachers()->pluck('id'))
                ->with(['teacher.user', 'evaluator', 'class'])
                ->latest()
                ->take(5)
                ->get();

            return view('hod.dashboard', [
                'department' => $department,
                'stats' => $stats,
                'evaluations' => $recentEvaluations,
                'currentCycle' => $currentCycle,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching HOD dashboard data: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to load dashboard.');
        }
    }

    public function teachers()
    {
        try {
            $hod = Auth::user();
            if (!$hod->hod) {
                Log::error('HOD user ID ' . $hod->id . ' has no associated Hod record.');
                return redirect()->route('hod.dashboard')->with('error', 'No HOD profile found.');
            }

            $department = $hod->hod->department;
            if (!$department) {
                Log::error('HOD ID ' . $hod->hod->id . ' has no associated department.');
                return redirect()->route('hod.dashboard')->with('error', 'No department assigned.');
            }

            $teachers = $department->teachers()
                ->withCount(['classes', 'evaluationsReceived'])
                ->get();

            return view('hod.teachers.index', compact('teachers'));
        } catch (\Exception $e) {
            Log::error('Error fetching teachers: ' . $e->getMessage());
            return redirect()->route('hod.dashboard')->with('error', 'Failed to load teachers.');
        }
    }

    public function showTeacher(User $teacher)
    {
        if ($teacher->department_id !== Auth::user()->department_id) {
            Log::warning('Unauthorized teacher access attempt', ['user_id' => Auth::id(), 'teacher_id' => $teacher->id]);
            abort(403, 'Unauthorized action.');
        }

        $teacher->load(['classes.students', 'evaluationsReceived.evaluator']);
        return view('hod.teachers.show', compact('teacher'));
    }

    public function reviewEvaluations()
    {
        try {
            $hod = Auth::user();
            $department = $hod->hod->department;
            if (!$department) {
                Log::error('HOD ID ' . $hod->hod->id . ' has no associated department.');
                return redirect()->route('hod.dashboard')->with('error', 'No department assigned.');
            }

            $currentCycle = EvaluationCycle::where('is_active', true)->first();
            if (!$currentCycle) {
                return redirect()->route('hod.dashboard')->with('error', 'No active evaluation cycle.');
            }

            $evaluations = Evaluation::whereIn('teacher_id', $department->teachers()->pluck('id'))
                ->where('evaluation_type', 'self')
                ->where('status', 'pending')
                ->where('evaluation_cycle_id', $currentCycle->id)
                ->with(['teacher.user', 'evaluator'])
                ->paginate(10);

            return view('hod.evaluation.index', compact('evaluations'));
        } catch (\Exception $e) {
            Log::error('Error fetching evaluations: ' . $e->getMessage());
            return redirect()->route('hod.dashboard')->with('error', 'Failed to load evaluations.');
        }
    }

    public function classes()
    {
        try {
            $hod = Auth::user();
            $department = $hod->hod->department;
            if (!$department) {
                Log::error('HOD ID ' . $hod->hod->id . ' has no associated department.');
                return redirect()->route('hod.dashboard')->with('error', 'No department assigned.');
            }

            $classes = $department->classes()
                ->with(['teacher', 'students'])
                ->withCount('students')
                ->get();

            return view('hod.classes.index', compact('classes'));
        } catch (\Exception $e) {
            Log::error('Error fetching classes: ' . $e->getMessage());
            return redirect()->route('hod.dashboard')->with('error', 'Failed to load classes.');
        }
    }

    public function selfEvaluations()
    {
        try {
            $hod = Auth::user();
            if (!$hod->hod || $hod->role !== 'hod') {
                Log::error('Unauthorized access to self-evaluations', ['user_id' => $hod->id]);
                abort(403, 'Unauthorized access');
            }

            $departmentId = $hod->hod->department_id;
            $currentCycle = EvaluationCycle::where('is_active', true)->first();
            if (!$currentCycle) {
                return redirect()->route('hod.dashboard')->with('error', 'No active evaluation cycle.');
            }

            $selfEvaluations = Evaluation::where('evaluation_type', 'self')
                ->where('evaluation_cycle_id', $currentCycle->id)
                ->whereHas('teacher', function ($query) use ($departmentId) {
                    $query->whereHas('subject', function ($q) use ($departmentId) {
                        $q->where('department_id', $departmentId);
                    });
                })
                ->with(['teacher.user', 'subject'])
                ->get();

            return view('hod.self-evaluations', compact('selfEvaluations'));
        } catch (\Exception $e) {
            Log::error('Error fetching self-evaluations: ' . $e->getMessage());
            return redirect()->route('hod.dashboard')->with('error', 'Failed to load self-evaluations.');
        }
    }

    public function approveSelfEvaluation(Request $request, $id)
    {
        try {
            $evaluation = Evaluation::findOrFail($id);
            if ($evaluation->evaluation_type !== 'self' || $evaluation->status !== 'pending') {
                Log::warning('Invalid approval attempt', ['evaluation_id' => $id, 'user_id' => Auth::id()]);
                return redirect()->back()->with('error', 'Invalid evaluation or status.');
            }

            if ($evaluation->teacher->department_id !== Auth::user()->hod->department_id) {
                Log::warning('Unauthorized approval attempt', ['evaluation_id' => $id, 'user_id' => Auth::id()]);
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'hod_comments' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();
            $evaluation->update([
                'status' => 'approved',
                'hod_comments' => $validated['hod_comments'] ?? null,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
            DB::commit();

            Log::info('Self-evaluation approved', ['evaluation_id' => $id, 'user_id' => Auth::id()]);
            return redirect()->route('hod.self-evaluations')->with('success', 'Self-evaluation approved.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Self-evaluation approval failed', ['evaluation_id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to approve self-evaluation: ' . $e->getMessage());
        }
    }

    public function rejectSelfEvaluation(Request $request, $id)
    {
        try {
            $evaluation = Evaluation::findOrFail($id);
            if ($evaluation->evaluation_type !== 'self' || $evaluation->status !== 'pending') {
                Log::warning('Invalid rejection attempt', ['evaluation_id' => $id, 'user_id' => Auth::id()]);
                return redirect()->back()->with('error', 'Invalid evaluation or status.');
            }

            if ($evaluation->teacher->department_id !== Auth::user()->hod->department_id) {
                Log::warning('Unauthorized rejection attempt', ['evaluation_id' => $id, 'user_id' => Auth::id()]);
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'hod_comments' => 'required|string|max:1000',
            ]);

            DB::beginTransaction();
            $evaluation->update([
                'status' => 'rejected',
                'hod_comments' => $validated['hod_comments'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
            DB::commit();

            Log::info('Self-evaluation rejected', ['evaluation_id' => $id, 'user_id' => Auth::id()]);
            return redirect()->route('hod.self-evaluations')->with('success', 'Self-evaluation rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Self-evaluation rejection failed', ['evaluation_id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to reject self-evaluation: ' . $e->getMessage());
        }
    }
}