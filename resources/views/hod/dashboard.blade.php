@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f5f5f5;
        min-height: 100vh;
        position: relative;
    }
    .container h2 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }
    .logout-form {
        position: absolute;
        top: 20px;
        right: 20px;
    }
    .logout-btn {
        background-color: #dc3545;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        font-size: 14px;
    }
    .logout-btn:hover {
        background-color: #c82333;
    }
    .card {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .card h3 {
        font-size: 20px;
        font-weight: 600;
        color: #444;
        margin-bottom: 15px;
    }
    .card p.empty {
        color: #666;
    }
    .card ul {
        list-style: none;
        padding: 0;
    }
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .evaluation-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .stat-item span, .evaluation-item span {
        color: #333;
    }
    .action-links {
        margin-bottom: 20px;
    }
    .action-links a {
        background-color: #007bff;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        margin-right: 10px;
        transition: background-color 0.2s;
    }
    .action-links a:hover {
        background-color: #0056b3;
    }
</style>

<div class="container">
    <!-- Logout Button -->
    <form action="{{ route('logout') }}" method="POST" class="logout-form" onsubmit="return confirm('Are you sure you want to log out?');">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <h2>HOD Dashboard - {{ $department->name }}</h2>
    <!-- Current Evaluation Cycle -->
    <div class="card">
        <h3>Current Evaluation Cycle</h3>
        <p>{{ $currentCycle ? $currentCycle->name : 'No active cycle' }}</p>
    </div>
    <!-- Action Links -->
    <div class="action-links">
        <a href="{{ route('hod.self-evaluations') }}">Review Teacher Self-Evaluations</a>
        <a href="{{ route('hod.report') }}">View Teacher Reports</a>
    </div>

    
    <!-- Department Statistics -->
    <div class="card stats">
        <h3>Department Statistics</h3>
        <ul>
            <li class="stat-item">
                <span>Number of Teachers</span>
                <span>{{ $stats['teacher_count'] }}</span>
            </li>
            <li class="stat-item">
                <span>Number of Subjects</span>
                <span>{{ $stats['subjects_count'] }}</span>
            </li>
            <li class="stat-item">
                <span>Pending Evaluations</span>
                <span>{{ $stats['pending_evaluation'] }}</span>
            </li>
        </ul>
    </div>

    <!-- Recent Evaluations -->
<div class="card evaluations">
    <h3>Recent Evaluations</h3>
    @if($evaluations->isEmpty())
        <p class="empty">No recent evaluations available.</p>
    @else
        <ul>
            @foreach($evaluations as $evaluation)
                <li class="evaluation-item">
                    <span>
                        Teacher: {{ $evaluation->teacher->name ?? 'Teacher not found' }} |
                        Evaluator: {{ $evaluation->evaluator->name ?? 'Evaluator not found' }} |
                        Class: {{ $evaluation->class ? $evaluation->class->name : 'Class not found' }} |
                        Score: {{ $evaluation->score }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
</div>
@endsection