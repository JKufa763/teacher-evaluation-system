<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Department;
use Illuminate\Http\Request;

class DeputyController extends Controller
{
    public function dashboard()
    {
        $pendingApprovals = Evaluation::where('status', 'pending')->count();
        $departments = Department::withCount('teachers')->get();
        
        return view('admin.dashboard', compact('pendingApprovals', 'departments'));
    }

    public function approveEvaluations()
    {
        $evaluations = Evaluation::with(['teacher', 'evaluator'])
                               ->where('status', 'pending')
                               ->get();
                               
        return view('deputy.approvals', compact('evaluations'));
    }
}