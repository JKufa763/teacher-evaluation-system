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
    .container h1 {
        font-size: 28px;
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
        font-style: italic;
    }
    .class-item {
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 10px;
        font-size: 16px;
        color: #333;
    }
    .action-buttons .btn {
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 6px;
        transition: background-color 0.2s;
    }
    .action-buttons .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .action-buttons .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>

<div class="container">
    <!-- Logout Button -->
    <form action="{{ route('logout') }}" method="POST" class="logout-form" onsubmit="return confirm('Are you sure you want to log out?');">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <h1>Teacher Dashboard</h1>

    <!-- Current Evaluation Cycle -->
    <div class="card">
        <div class="card-body">
            <h3>Current Evaluation Cycle</h3>
            <p>{{ $currentCycle ? $currentCycle->name : 'No active cycle' }}</p>
        </div>
    </div>

    <!-- My Classes -->
    <div class="card">
        <div class="card-body">
            <h3>My Classes</h3>
            @if($classes->isEmpty())
                <p class="empty">No classes assigned.</p>
            @else
                <ul class="list-unstyled">
                    @foreach($classes as $class)
                        <li class="class-item">{{ $class->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('teacher.evaluation-report') }}" class="btn btn-primary me-2">View Evaluation Report</a>
        <a href="{{ route('teacher.self-evaluate') }}" class="btn btn-primary me-2">Self Evaluation</a>
        <a href="{{ route('teacher.peer-evaluate') }}" class="btn btn-primary">Peer Evaluations</a>
    </div>
</div>
@endsection