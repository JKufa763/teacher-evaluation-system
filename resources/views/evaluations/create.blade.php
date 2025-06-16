@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f5f5f5;
        min-height: 100vh;
    }
    .container h1 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }
    .teacher-card {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border-left: 5px solid #f4d03f;
    }
    .teacher-card h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .teacher-card p {
        color: #666;
        margin-bottom: 5px;
    }
    .form-card {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 20px;
        max-width: 500px;
        margin: 0 auto;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #444;
        font-weight: 500;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #f4d03f;
    }
    .form-group textarea {
        resize: vertical;
    }
    .submit-btn {
        background-color: #f4d03f;
        color: #fff;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .submit-btn:hover {
        background-color: #f1c40f;
    }
    .back-btn {
        display: inline-block;
        margin-bottom: 15px;
        color:rgb(194, 43, 26);
        text-decoration: none;
        font-weight: 500;
    }
    .back-btn:hover {
        color:rgb(139, 205, 77);
    }
</style>

<div class="container">
    <a href="{{ route('student.dashboard') }}" class="back-btn">Back to Dashboard</a>

    <h1>Evaluate {{ $subject->name }}</h1>

    <!-- Highlighted Teacher Details -->
    @if($teacher)
        <div class="teacher-card">
            <h3>Teacher Details</h3>
            <p><strong>Name:</strong> {{ $teacher->user->name }}</p>
            <p><strong>Department:</strong> {{ $teacher->department->name ?? 'Not assigned' }}</p>
            <p><strong>Role:</strong> {{ $teacher->user->role }}</p>
        </div>
    @else
        <div class="teacher-card">
            <p>Teacher: Not assigned</p>
        </div>
    @endif

    <form method="POST" action="{{ route('evaluations.store') }}" class="form-card">
        @csrf
        <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

        <div class="form-group">
            <label for="knowledge_rating">Knowledge of Subject (1-5)</label>
            <input type="number" name="knowledge_rating" min="1" max="5" required>
        </div>

        <div class="form-group">
            <label for="teaching_skill">Teaching Skill (1-5)</label>
            <input type="number" name="teaching_skill" min="1" max="5" required>
        </div>

        <div class="form-group">
            <label for="communication">Communication (1-5)</label>
            <input type="number" name="communication" min="1" max="5" required>
        </div>

        <div class="form-group">
            <label for="punctuality">Punctuality (1-5)</label>
            <input type="number" name="punctuality" min="1" max="5" required>
        </div>

        <div class="form-group">
            <label for="comments">Comments</label>
            <textarea name="comments"></textarea>
        </div>

        <button type="submit" class="submit-btn">Submit Evaluation</button>
    </form>
</div>
@endsection