<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Evaluation;
use App\Models\Department;
use App\Models\Class_;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    public function teacherReport()
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            Log::warning('No teacher record found for user', ['user_id' => Auth::user()->id]);
            return redirect()->back()->with('error', 'Teacher profile not found.');
        }

        $reportData = $this->generateTeacherReport($teacher->id);
        return view('reports.teacher-report', compact('reportData'));
    }

    // Teacher Report PDF Export
    public function exportTeacherReport()
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher || Auth::user()->role !== 'teacher') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $reportData = $this->generateTeacherReport($teacher->id);
        $pdf = Pdf::loadView('reports.teacher-report-pdf', compact('reportData'));
        return $pdf->download('teacher-evaluation-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // HOD's Reports (Per Teacher and Department Summary)
    public function hodReports()
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'hod') {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            $hod = $user->hod;
            if (!$hod) {
                \Log::error('HOD user ID ' . $user->id . ' has no associated Hod record.');
                return redirect()->route('hod.dashboard')->with('error', 'No HOD profile found. Contact the administrator.');
            }

            $department = $hod->department;
            if (!$department) {
                \Log::error('HOD ID ' . $hod->id . ' has no associated department.');
                return redirect()->route('hod.dashboard')->with('error', 'No department assigned to this HOD. Contact the administrator.');
            }

            $teachers = Teacher::where('department_id', $department->id)->get();
            $teacherReports = [];
            foreach ($teachers as $teacher) {
                $teacherReports[$teacher->id] = $this->generateTeacherReport($teacher->id);
            }

            $departmentSummary = $this->generateDepartmentSummary($department->id);
            return view('reports.hod-report', compact('teacherReports', 'departmentSummary'));
        } catch (\Exception $e) {
            \Log::error('Error generating HOD reports: ' . $e->getMessage());
            return redirect()->route('hod.dashboard')->with('error', 'Failed to load reports. Please try again later.');
        }
    }

    // HOD Reports PDF Export
    public function exportHodReports()
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'hod') {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            $hod = $user->hod;
            if (!$hod) {
                \Log::error('HOD user ID ' . $user->id . ' has no associated Hod record.');
                return redirect()->route('hod.dashboard')->with('error', 'No HOD profile found.');
            }

            $department = $hod->department;
            if (!$department) {
                \Log::error('HOD ID ' . $hod->id . ' has no associated department.');
                return redirect()->route('hod.dashboard')->with('error', 'No department assigned.');
            }

            $teachers = Teacher::where('department_id', $department->id)->with('user')->get();
            $teacherReports = [];
            foreach ($teachers as $teacher) {
                $teacherReports[$teacher->id] = $this->generateTeacherReport($teacher->id);
            }

            $departmentSummary = $this->generateDepartmentSummary($department->id);
            $pdf = Pdf::loadView('reports.hod-report-pdf', compact('teacherReports', 'departmentSummary', 'department'));
            return $pdf->download('department-evaluation-report-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error exporting HOD reports: ' . $e->getMessage());
            return redirect()->route('hod.dashboard')->with('error', 'Failed to export reports.');
        }
    }


    // System Admin's Reports (All Teachers, All Departments, School Average)
    public function adminReports()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $teachers = Teacher::all();
        $teacherReports = [];
        foreach ($teachers as $teacher) {
            $teacherReports[$teacher->id] = $this->generateTeacherReport($teacher->id);
        }

        $departments = Department::all();
        $departmentSummaries = [];
        foreach ($departments as $department) {
            $departmentSummaries[$department->id] = $this->generateDepartmentSummary($department->id);
        }

        $schoolAverage = $this->generateSchoolAverage();
        return view('reports.admin-report', compact('teacherReports', 'departmentSummaries', 'schoolAverage'));
    }

    // Admin Reports PDF Export
    public function exportAdminReports()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $teachers = Teacher::all();
        $teacherReports = [];
        foreach ($teachers as $teacher) {
            $teacherReports[$teacher->id] = $this->generateTeacherReport($teacher->id);
        }

        $departments = Department::all();
        $departmentSummaries = [];
        foreach ($departments as $department) {
            $departmentSummaries[$department->id] = $this->generateDepartmentSummary($department->id);
        }

        $schoolAverage = $this->generateSchoolAverage();
        $pdf = Pdf::loadView('reports.admin-report-pdf', compact('teacherReports', 'departmentSummaries', 'schoolAverage'));
        return $pdf->download('school-evaluation-report-' . now()->format('Y-m-d') . '.pdf');
    }


    private function generateTeacherReport($teacherId)
    {
        // Get all evaluations for the teacher, including historical ones
        $selfEvaluations = Evaluation::where('teacher_id', $teacherId)
            ->where('evaluation_type', 'self')
            ->where('completed', true)
            ->whereIn('status', ['approved', 'closed'])
            ->get();

        $peerEvaluations = Evaluation::where('teacher_id', $teacherId)
            ->where('evaluation_type', 'peer')
            ->where('completed', true)
            ->whereIn('status', ['approved', 'closed'])
            ->get();

        $studentEvaluations = Evaluation::where('teacher_id', $teacherId)
            ->where('evaluation_type', 'student')
            ->where('completed', true)
            ->whereIn('status', ['approved', 'closed'])
            ->get();

        // Calculate average ratings
        $selfAvgRating = $selfEvaluations->avg('average_rating') ?? 0;
        $peerAvgRating = $peerEvaluations->avg('average_rating') ?? 0;
        $studentAvgRating = $studentEvaluations->avg('average_rating') ?? 0;

        // Only self-evaluations have scores
        $selfAvgScore = $selfEvaluations->avg('score') ?? 0;
        $peerAvgScore = null;
        $studentAvgScore = null;

        $weightedScore = null;

        // Calculate weighted rating
        $weightedRating = ($selfAvgRating * 0.5) + ($peerAvgRating * 0.3) + ($studentAvgRating * 0.2);

        // Current performance breakdown
        $criteria = ['knowledge_rating', 'teaching_skill', 'communication', 'punctuality'];
        $currentPerformance = [];
        foreach ($criteria as $criterion) {
            $currentPerformance[$criterion] = [
                'self' => $selfEvaluations->avg($criterion) ?? 0,
                'peer' => $peerEvaluations->avg($criterion) ?? 0,
                'student' => $studentEvaluations->avg($criterion) ?? 0,
                'weighted' => (
                    ($selfEvaluations->avg($criterion) ?? 0) * 0.5 +
                    ($peerEvaluations->avg($criterion) ?? 0) * 0.3 +
                    ($studentEvaluations->avg($criterion) ?? 0) * 0.2
                ),
            ];
        }

        // Identify weak areas
        $weakAreas = array_filter($currentPerformance, fn($ratings) => $ratings['weighted'] < 3);

        // Extract performance planning and attributes
        $selfEvaluationDetails = $selfEvaluations->map(function ($eval) {
            $comments = json_decode($eval->comments, true) ?? [];
            return [
                'performance_planning' => $comments['performance_planning'] ?? [],
                'personal_attributes' => $comments['personal_attributes'] ?? [],
                'score' => $eval->score,
            ];
        });

        // Past performance trends - now including all historical cycles
        $pastPerformance = Evaluation::select(
            \DB::raw('evaluation_cycles.name as label'),
            \DB::raw('evaluation_cycles.id as cycle_id'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "self" THEN knowledge_rating END) as self_knowledge'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "peer" THEN knowledge_rating END) as peer_knowledge'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "student" THEN knowledge_rating END) as student_knowledge'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "self" THEN teaching_skill END) as self_teaching'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "peer" THEN teaching_skill END) as peer_teaching'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "student" THEN teaching_skill END) as student_teaching'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "self" THEN communication END) as self_communication'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "peer" THEN communication END) as peer_communication'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "student" THEN communication END) as student_communication'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "self" THEN punctuality END) as self_punctuality'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "peer" THEN punctuality END) as peer_punctuality'),
            \DB::raw('AVG(CASE WHEN evaluation_type = "student" THEN punctuality END) as student_punctuality')
        )
            ->join('evaluation_cycles', 'evaluations.evaluation_cycle_id', '=', 'evaluation_cycles.id')
            ->where('teacher_id', $teacherId)
            ->whereIn('evaluations.status', ['approved', 'closed'])
            ->where('evaluations.completed', true)
            ->groupBy('evaluation_cycles.id', 'evaluation_cycles.name')
            ->orderBy('evaluation_cycles.id')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->label,
                    'cycle_id' => $item->cycle_id,
                    'knowledge' => [
                        'self' => $item->self_knowledge ?? 0,
                        'peer' => $item->peer_knowledge ?? 0,
                        'student' => $item->student_knowledge ?? 0,
                        'weighted' => (
                            ($item->self_knowledge ?? 0) * 0.5 +
                            ($item->peer_knowledge ?? 0) * 0.3 +
                            ($item->student_knowledge ?? 0) * 0.2
                        ),
                    ],
                    'teaching' => [
                        'self' => $item->self_teaching ?? 0,
                        'peer' => $item->peer_teaching ?? 0,
                        'student' => $item->student_teaching ?? 0,
                        'weighted' => (
                            ($item->self_teaching ?? 0) * 0.5 +
                            ($item->peer_teaching ?? 0) * 0.3 +
                            ($item->student_teaching ?? 0) * 0.2
                        ),
                    ],
                    'communication' => [
                        'self' => $item->self_communication ?? 0,
                        'peer' => $item->peer_communication ?? 0,
                        'student' => $item->student_communication ?? 0,
                        'weighted' => (
                            ($item->self_communication ?? 0) * 0.5 +
                            ($item->peer_communication ?? 0) * 0.3 +
                            ($item->student_communication ?? 0) * 0.2
                        ),
                    ],
                    'punctuality' => [
                        'self' => $item->self_punctuality ?? 0,
                        'peer' => $item->peer_punctuality ?? 0,
                        'student' => $item->student_punctuality ?? 0,
                        'weighted' => (
                            ($item->self_punctuality ?? 0) * 0.5 +
                            ($item->peer_punctuality ?? 0) * 0.3 +
                            ($item->student_punctuality ?? 0) * 0.2
                        ),
                    ],
                ];
            });

        return [
            'teacher' => Teacher::find($teacherId),
            'selfEvaluations' => $selfEvaluations,
            'peerEvaluations' => $peerEvaluations,
            'studentEvaluations' => $studentEvaluations,
            'selfAvgRating' => $selfAvgRating,
            'peerAvgRating' => $peerAvgRating,
            'studentAvgRating' => $studentAvgRating,
            'selfAvgScore' => $selfAvgScore,
            'peerAvgScore' => $peerAvgScore,
            'studentAvgScore' => $studentAvgScore,
            'weightedRating' => $weightedRating,
            'weightedScore' => $weightedScore,
            'currentPerformance' => $currentPerformance,
            'weakAreas' => $weakAreas,
            'pastPerformance' => $pastPerformance,
            'selfEvaluationDetails' => $selfEvaluationDetails,
        ];
    }

    // Helper: Generate Department Summary
    private function generateDepartmentSummary($departmentId)
    {
        $teachers = Teacher::where('department_id', $departmentId)->get();
        $selfRatings = [];
        $peerRatings = [];
        $studentRatings = [];
        $selfScores = [];
        $peerScores = [];
        $studentScores = [];
        $weightedRatings = [];
        $weightedScores = [];

        foreach ($teachers as $teacher) {
            $report = $this->generateTeacherReport($teacher->id);
            $selfRatings[] = $report['selfAvgRating'];
            $peerRatings[] = $report['peerAvgRating'];
            $studentRatings[] = $report['studentAvgRating'];
            $selfScores[] = $report['selfAvgScore'];
            $peerScores[] = $report['peerAvgScore'];
            $studentScores[] = $report['studentAvgScore'];
            $weightedRatings[] = $report['weightedRating'];
            $weightedScores[] = $report['weightedScore'];
        }

        return [
            'department' => Department::find($departmentId),
            'avgSelfRating' => count($selfRatings) ? array_sum($selfRatings) / count($selfRatings) : 0,
            'avgPeerRating' => count($peerRatings) ? array_sum($peerRatings) / count($peerRatings) : 0,
            'avgStudentRating' => count($studentRatings) ? array_sum($studentRatings) / count($studentRatings) : 0,
            'avgSelfScore' => count($selfScores) ? array_sum($selfScores) / count($selfScores) : 0,
            'avgPeerScore' => count($peerScores) ? array_sum($peerScores) / count($peerScores) : 0,
            'avgStudentScore' => count($studentScores) ? array_sum($studentScores) / count($studentScores) : 0,
            'avgWeightedRating' => count($weightedRatings) ? array_sum($weightedRatings) / count($weightedRatings) : 0,
            'avgWeightedScore' => count($weightedScores) ? array_sum($weightedScores) / count($weightedScores) : 0,
        ];
    }

    // Helper: Generate School Average
    private function generateSchoolAverage()
    {
        $teachers = Teacher::all();
        $selfRatings = [];
        $peerRatings = [];
        $studentRatings = [];
        $selfScores = [];
        $peerScores = [];
        $studentScores = [];
        $weightedRatings = [];
        $weightedScores = [];

        foreach ($teachers as $teacher) {
            $report = $this->generateTeacherReport($teacher->id);
            $selfRatings[] = $report['selfAvgRating'];
            $peerRatings[] = $report['peerAvgRating'];
            $studentRatings[] = $report['studentAvgRating'];
            $selfScores[] = $report['selfAvgScore'];
            $peerScores[] = $report['peerAvgScore'];
            $studentScores[] = $report['studentAvgScore'];
            $weightedRatings[] = $report['weightedRating'];
            $weightedScores[] = $report['weightedScore'];
        }

        return [
            'avgSelfRating' => count($selfRatings) ? array_sum($selfRatings) / count($selfRatings) : 0,
            'avgPeerRating' => count($peerRatings) ? array_sum($peerRatings) / count($peerRatings) : 0,
            'avgStudentRating' => count($studentRatings) ? array_sum($studentRatings) / count($studentRatings) : 0,
            'avgSelfScore' => count($selfScores) ? array_sum($selfScores) / count($selfScores) : 0,
            'avgPeerScore' => count($peerScores) ? array_sum($peerScores) / count($peerScores) : 0,
            'avgStudentScore' => count($studentScores) ? array_sum($studentScores) / count($studentScores) : 0,
            'avgWeightedRating' => count($weightedRatings) ? array_sum($weightedRatings) / count($weightedRatings) : 0,
            'avgWeightedScore' => count($weightedScores) ? array_sum($weightedScores) / count($weightedScores) : 0,
        ];
    }

    
}



