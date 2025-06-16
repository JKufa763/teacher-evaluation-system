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
    .pending-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #fff9e6;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: background-color 0.2s;
    }
    .pending-item:hover {
        background-color: #fff7cc;
    }
    .pending-item a {
        background-color: #f4d03f;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .pending-item a:hover {
        background-color: #f1c40f;
    }
    .completed-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #e6ffe6;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: background-color 0.2s;
    }
    .completed-item:hover {
        background-color: #ccffcc;
    }
    .completed-item a {
        background-color: #28a745;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .completed-item a:hover {
        background-color: #218838;
    }
    .subject-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .subject-item span {
        color: #333;
    }
    .status-completed {
        color: #28a745;
    }
    .status-pending {
        color: #f4d03f;
    }
</style>

<div class="container">
    <!-- Logout Button -->
    <form action="{{ route('logout') }}" method="POST" class="logout-form" onsubmit="return confirm('Are you sure you want to log out?');">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <h2>Student Dashboard</h2>

    <!-- Current Evaluation Cycle -->
    <div class="card">
        <div class="card-body">
            <h3>Current Evaluation Cycle</h3>
            <p>{{ $currentCycle ? $currentCycle->name : 'No active cycle' }}</p>
        </div>
    </div>

    <!-- Pending Evaluations Section -->
    <div class="card pending-evaluations">
        <h3>Pending Evaluations</h3>
        @if($pendingEvaluations->isEmpty())
            <p class="empty">No pending evaluations at this time.</p>
        @else
            <ul>
                @foreach($pendingEvaluations as $evaluation)
                    <li class="pending-item">
                        <span>{{ $evaluation->subject->name }}</span>
                        <a href="{{ route('evaluations.showForm', $evaluation->id) }}">Complete Evaluation</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Completed Evaluations Section -->
    <div class="card completed-evaluations">
        <h3>Completed Evaluations</h3>
        @if($completedEvaluations->isEmpty())
            <p class="empty">No completed evaluations.</p>
        @else
            <ul>
                @foreach($completedEvaluations as $evaluation)
                    <li class="completed-item">
                        <span>{{ $evaluation->subject->name }}</span>
                        
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Your Subjects Section -->
    <div class="card your-subjects">
        <h3>Your Subjects</h3>
        <ul>
            @foreach($subjects as $subject)
                <li class="subject-item">
                    <span>{{ $subject->name }}</span>
                    <span class="{{ $subject->pivot->completed ? 'status-completed' : 'status-pending' }}">
                        {{ $subject->pivot->completed ? 'Evaluation Completed' : 'Pending Evaluation' }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection