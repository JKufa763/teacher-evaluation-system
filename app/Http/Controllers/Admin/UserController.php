<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Teacher, Student, Subject, Evaluation, Grade, Department, EvaluationCycle, Class_};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $stats = [
            'total_users' => User::count(),
            'active_teachers' => Teacher::count(),
            'total_departments' => Department::count(),
            'total_students' => Student::count(),
        ];

        $departments = Department::withCount('teachers')->get()->map(function ($department) {
            $subjectIds = $department->teachers->flatMap->subjects->pluck('id')->unique();
            $studentsCount = Student::whereHas('subjects', function ($query) use ($subjectIds) {
                $query->whereIn('subjects.id', $subjectIds);
            })->distinct()->count();
            $department->students_count = $studentsCount;
            return $department;
        });

        $currentCycle = EvaluationCycle::where('is_active', true)->first();

        return view('admin.dashboard', compact('stats', 'departments', 'currentCycle'));
    }

    public function cycles()
    {
        $cycles = EvaluationCycle::orderBy('created_at', 'desc')->get();
        return view('admin.cycles', compact('cycles'));
    }

    public function storeCycle(Request $request)
    {
        $validated = $request->validate([
            'cycle_name' => 'required|string|unique:evaluation_cycles,name',
        ]);

        try {
            DB::beginTransaction();

            // Get the current active cycle before deactivating
            $currentCycle = EvaluationCycle::where('is_active', true)->first();

            // Deactivate all cycles
            EvaluationCycle::where('is_active', true)->update(['is_active' => false]);

            // Create and activate new cycle
            $newCycle = EvaluationCycle::create([
                'name' => $validated['cycle_name'],
                'is_active' => true,
            ]);

            // Archive completed evaluations from previous cycles
            if ($currentCycle) {
                Evaluation::where('evaluation_cycle_id', $currentCycle->id)
                    ->where('completed', true)
                    ->update(['status' => 'closed']);
            }

            // Delete incomplete evaluations from the current cycle only
            if ($currentCycle) {
                Evaluation::where('evaluation_cycle_id', $currentCycle->id)
                    ->where('completed', false)
                    ->delete();
            }

            // Get all active student-subject relationships
            $studentSubjects = DB::table('student_subject')
                ->select('student_id', 'subject_id', 'class_id')
                ->where('completed', false)
                ->get();

            Log::info('Fetching student_subject records for new cycle', ['count' => $studentSubjects->count()]);

            $evaluations = [];
            foreach ($studentSubjects as $record) {
                $subject = Subject::find($record->subject_id);
                if (!$subject) {
                    Log::warning('Subject not found', ['subject_id' => $record->subject_id]);
                    continue;
                }

                $teacher = Teacher::where('subject_id', $record->subject_id)->first();
                if (!$teacher) {
                    Log::warning('Teacher not found for subject', ['subject_id' => $record->subject_id]);
                    continue;
                }

                $student = Student::find($record->student_id);
                if (!$student) {
                    Log::warning('Student not found', ['student_id' => $record->student_id]);
                    continue;
                }

                $evaluations[] = [
                    'student_id' => $record->student_id,
                    'teacher_id' => $teacher->id,
                    'subject_id' => $record->subject_id,
                    'class_id' => $record->class_id,
                    'evaluation_type' => 'student',
                    'user_id' => $student->user_id,
                    'completed' => false,
                    'status' => 'pending',
                    'evaluation_cycle_id' => $newCycle->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($evaluations)) {
                Evaluation::insert($evaluations);
                Log::info('Student evaluations created for new cycle', [
                    'cycle_id' => $newCycle->id,
                    'count' => count($evaluations)
                ]);
            } else {
                Log::warning('No student evaluations created for new cycle', ['cycle_id' => $newCycle->id]);
            }

            DB::commit();
            return redirect()->route('admin.cycles')->with('success', 'New evaluation cycle set successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create evaluation cycle', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create cycle: ' . $e->getMessage());
        }
    }

    public function resetEvaluations(Request $request)
    {
        $currentCycle = EvaluationCycle::where('is_active', true)->first();
        if (!$currentCycle) {
            return redirect()->route('admin.cycles')->with('error', 'No active cycle found.');
        }

        try {
            DB::beginTransaction();

            // Archive completed evaluations
            Evaluation::where('evaluation_cycle_id', $currentCycle->id)
                ->where('completed', true)
                ->update(['status' => 'closed']);

            // Delete incomplete evaluations
            Evaluation::where('evaluation_cycle_id', $currentCycle->id)
                ->where('completed', false)
                ->delete();

            // Recreate student evaluation records
            $this->createStudentEvaluations($currentCycle->id);

            DB::commit();
            Log::info('Evaluations reset for cycle', ['cycle_id' => $currentCycle->id]);
            return redirect()->route('admin.cycles')->with('success', "Evaluations reset for cycle: {$currentCycle->name}");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Evaluation reset failed', ['cycle_id' => $currentCycle->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to reset evaluations: ' . $e->getMessage());
        }
    }

    public function reports()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $stats = [
            'total_evaluations' => Evaluation::count(),
            'approved_evaluations' => Evaluation::where('status', 'approved')->count(),
            'pending_evaluations' => Evaluation::where('status', 'pending')->count(),
        ];

        $departments = Department::with('teachers.evaluations')->get()->map(function ($department) {
            $evaluations = $department->teachers->flatMap->evaluations->whereIn('status', ['approved', 'closed']);
            $department->avg_knowledge_rating = $evaluations->avg('knowledge_rating') ?? 0;
            $department->avg_teaching_skill = $evaluations->avg('teaching_skill') ?? 0;
            $department->avg_communication = $evaluations->avg('communication') ?? 0;
            $department->avg_punctuality = $evaluations->avg('punctuality') ?? 0;
            return $department;
        });

        $topTeachers = Teacher::with(['user', 'department'])
            ->select('teachers.*')
            ->leftJoin('evaluations', 'teachers.id', '=', 'evaluations.teacher_id')
            ->whereIn('evaluations.status', ['approved', 'closed'])
            ->groupBy('teachers.id')
            ->selectRaw('AVG(evaluations.score) as avg_score')
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        return view('reports.admin-report', compact('stats', 'departments', 'topTeachers'));
    }

    public function index()
    {
        $users = User::with(['subjects', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => ['admin', 'teacher', 'student', 'hod', 'deputy_head'],
            'subjects' => Subject::all(),
            'departments' => Department::all(),
            'classes' => Class_::with('grade')->get(),
            'grades' => Grade::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateUserRequest($request);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'] ?? $this->generateUniqueUsername($validated['name'], $validated['email']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'subject_id' => $validated['subject_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
        ]);

        try {
            DB::beginTransaction();

            if ($validated['role'] === 'teacher') {
                $validatedTeacher = $request->validate([
                    'subject_id' => 'required|exists:subjects,id',
                    'department_id' => 'required|exists:departments,id',
                    'class_ids' => 'nullable|array',
                    'class_ids.*' => 'exists:classes,id',
                ]);

                $teacher = Teacher::create([
                    'user_id' => $user->id,
                    'subject_id' => $validatedTeacher['subject_id'],
                    'department_id' => $validatedTeacher['department_id'],
                ]);

                if (!empty($validatedTeacher['class_ids'])) {
                    $classAssignments = [];
                    foreach ($validatedTeacher['class_ids'] as $classId) {
                        $classAssignments[] = [
                            'teacher_id' => $teacher->id,
                            'class_id' => $classId,
                            'subject_id' => $validatedTeacher['subject_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    DB::table('teacher_class')->insert($classAssignments);
                }
            } elseif ($validated['role'] === 'student') {
                $validatedStudent = $request->validate([
                    'class_id' => 'required|exists:classes,id',
                    'subject_ids' => 'required|array|size:4',
                    'subject_ids.*' => 'exists:subjects,id',
                ]);

                $student = Student::create([
                    'user_id' => $user->id,
                ]);

                // Assign student to class
                DB::table('student_class')->insert([
                    'student_id' => $student->id,
                    'class_id' => $validatedStudent['class_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Get current active evaluation cycle
                $currentCycle = EvaluationCycle::where('is_active', true)->first();
                if (!$currentCycle) {
                    throw new \Exception('No active evaluation cycle found');
                }

                // Assign student to subjects and create evaluations
                $subjectAssignments = [];
                $evaluations = [];
                
                foreach ($validatedStudent['subject_ids'] as $subjectId) {
                    $subjectAssignments[] = [
                        'student_id' => $student->id,
                        'subject_id' => $subjectId,
                        'class_id' => $validatedStudent['class_id'],
                        'user_id' => $user->id,
                        'completed' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Get teacher for this subject
                    $teacher = Teacher::where('subject_id', $subjectId)->first();
                    if ($teacher) {
                        $evaluations[] = [
                            'student_id' => $student->id,
                            'teacher_id' => $teacher->id,
                            'subject_id' => $subjectId,
                            'class_id' => $validatedStudent['class_id'],
                            'evaluation_type' => 'student',
                            'user_id' => $user->id,
                            'completed' => false,
                            'status' => 'pending',
                            'evaluation_cycle_id' => $currentCycle->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Insert subject assignments
                DB::table('student_subject')->insert($subjectAssignments);

                // Insert evaluations if any
                if (!empty($evaluations)) {
                    Evaluation::insert($evaluations);
                }
            } elseif ($validated['role'] === 'hod') {
                DB::table('hods')->insert([
                    'user_id' => $user->id,
                    'department_id' => $validated['department_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => ['admin', 'teacher', 'student', 'hod', 'deputy_head'],
            'subjects' => Subject::all(),
            'departments' => Department::all(),
            'classes' => Class_::with('grade')->get(),
            'grades' => Grade::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $this->validateUserRequest($request, $user);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'] ?? $this->generateUniqueUsername($validated['name'], $validated['email']),
            'email' => $validated['email'],
            'role' => $validated['role'],
            'subject_id' => $validated['subject_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($validated['role'] === 'teacher') {
            $validatedTeacher = $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'department_id' => 'required|exists:departments,id',
                'class_ids' => 'nullable|array',
                'class_ids.*' => 'exists:classes,id',
            ]);

            $teacher = $user->teacher ?? Teacher::create([
                'user_id' => $user->id,
                'subject_id' => $validatedTeacher['subject_id'],
                'department_id' => $validatedTeacher['department_id'],
            ]);

            $teacher->update([
                'subject_id' => $validatedTeacher['subject_id'],
                'department_id' => $validatedTeacher['department_id'],
            ]);

            DB::table('teacher_class')->where('teacher_id', $teacher->id)->delete();
            if (!empty($validatedTeacher['class_ids'])) {
                $classAssignments = [];
                foreach ($validatedTeacher['class_ids'] as $classId) {
                    $classAssignments[] = [
                        'teacher_id' => $teacher->id,
                        'class_id' => $classId,
                        'subject_id' => $validatedTeacher['subject_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('teacher_class')->insert($classAssignments);
            }
        } elseif ($validated['role'] === 'student') {
            $validatedStudent = $request->validate([
                'class_id' => 'required|exists:classes,id',
                'subject_ids' => 'required|array|size:4',
                'subject_ids.*' => 'exists:subjects,id',
            ]);

            $student = $user->student ?? Student::create([
                'user_id' => $user->id,
            ]);

            // Update class assignment
            DB::table('student_class')->where('student_id', $student->id)->delete();
            DB::table('student_class')->insert([
                'student_id' => $student->id,
                'class_id' => $validatedStudent['class_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get current active evaluation cycle
            $currentCycle = EvaluationCycle::where('is_active', true)->first();
            if (!$currentCycle) {
                throw new \Exception('No active evaluation cycle found');
            }

            // Update subject assignments
            DB::table('student_subject')->where('student_id', $student->id)->delete();
            $subjectAssignments = [];
            $evaluations = [];
            
            foreach ($validatedStudent['subject_ids'] as $subjectId) {
                $subjectAssignments[] = [
                    'student_id' => $student->id,
                    'subject_id' => $subjectId,
                    'class_id' => $validatedStudent['class_id'],
                    'user_id' => $user->id,
                    'completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Get teacher for this subject
                $teacher = Teacher::where('subject_id', $subjectId)->first();
                if ($teacher) {
                    $evaluations[] = [
                        'student_id' => $student->id,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subjectId,
                        'class_id' => $validatedStudent['class_id'],
                        'evaluation_type' => 'student',
                        'user_id' => $user->id,
                        'completed' => false,
                        'status' => 'pending',
                        'evaluation_cycle_id' => $currentCycle->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert subject assignments
            DB::table('student_subject')->insert($subjectAssignments);

            // Delete existing evaluations for the current cycle
            Evaluation::where('student_id', $student->id)
                ->where('evaluation_cycle_id', $currentCycle->id)
                ->delete();

            // Insert new evaluations if any
            if (!empty($evaluations)) {
                Evaluation::insert($evaluations);
            }
        } elseif ($validated['role'] === 'hod') {
            DB::table('hods')->where('user_id', $user->id)->delete();
            DB::table('hods')->insert([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        try {
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account!');
            }

            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    private function validateUserRequest(Request $request, $user = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'role' => ['required', Rule::in(['admin', 'teacher', 'student', 'hod', 'deputy_head'])],
            'password' => $user ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
        ];

        if (in_array($request->role, ['teacher', 'hod'])) {
            $rules['subject_id'] = 'required|exists:subjects,id';
            $rules['department_id'] = 'required|exists:departments,id';
        }

        return $request->validate($rules);
    }

    private function generateUniqueUsername($name, $email)
    {
        $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        if (strlen($username) < 3) {
            $username = strtolower(explode('@', $email)[0]);
        }

        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }

    public function approveEvaluations()
    {
        return view('admin.evaluations.approvals');
    }

    public function evaluationReport($teacher_id)
    {
        $teacher = Teacher::findOrFail($teacher_id);
        $evaluations = Evaluation::where('teacher_id', $teacher->id)
            ->whereIn('status', ['approved', 'closed'])
            ->get();

        $currentPerformance = [
            'knowledge_rating' => $evaluations->avg('knowledge_rating'),
            'teaching_skill' => $evaluations->avg('teaching_skill'),
            'communication' => $evaluations->avg('communication'),
            'punctuality' => $evaluations->avg('punctuality'),
        ];

        $weakAreas = array_filter($currentPerformance, fn($rating) => $rating < 3);

        $pastPerformance = Evaluation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('AVG(knowledge_rating) as avg_knowledge'),
            DB::raw('AVG(teaching_skill) as avg_teaching'),
            DB::raw('AVG(communication) as avg_communication'),
            DB::raw('AVG(punctuality) as avg_punctuality')
        )
            ->where('teacher_id', $teacher->id)
            ->whereIn('status', ['approved', 'closed'])
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

        return view('admin.evaluation-report', compact('teacher', 'currentPerformance', 'weakAreas', 'pastPerformance'));
    }
}