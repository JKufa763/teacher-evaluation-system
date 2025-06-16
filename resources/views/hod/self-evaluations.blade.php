@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Teacher Self-Evaluations</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($selfEvaluations->isEmpty())
        <p class="text-muted">No self-evaluations available.</p>
    @else
        @foreach($selfEvaluations as $evaluation)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Teacher: {{ $evaluation->teacher->user->name ?? 'Unknown' }} | Subject: {{ $evaluation->subject->name ?? 'N/A' }}</h5>
                </div>
                <div class="card-body">
                    <h6>Performance Planning</h6>
                    <table class="table table-bordered mb-3">
                        <thead class="table-light">
                            <tr><th>Criteria</th><th>Target</th><th>Actual Performance</th><th>Self-Assessed Score</th><th>Evidence</th></tr>
                        </thead>
                        <tbody>
                            @php
                                $comments = json_decode($evaluation->comments, true);
                                $performanceCriteria = [
                                    'lessons_delivered' => 'Lessons Delivered',
                                    'exercises_tests' => 'Exercises and Tests Administered',
                                    'records_maintained' => 'Records Maintained',
                                    'sporting_sessions' => 'Sporting Sessions Conducted',
                                ];
                            @endphp
                            @foreach($performanceCriteria as $key => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $comments['performance_planning'][$key]['target'] ?? 'N/A' }}</td>
                                    <td>{{ $comments['performance_planning'][$key]['actual_performance'] ?? 'N/A' }}</td>
                                    <td>{{ $comments['performance_planning'][$key]['score'] ?? 'N/A' }}</td>
                                    <td>{{ $comments['performance_planning'][$key]['evidence'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h6>Personal Attributes</h6>
                    <table class="table table-bordered mb-3">
                        <thead class="table-light">
                            <tr><th>Attribute</th><th>Rating</th></tr>
                        </thead>
                        <tbody>
                            @foreach($comments['personal_attributes'] ?? [] as $key => $rating)
                                <tr>
                                    <td>{{ str_replace('_', ' ', ucwords($key)) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $rating)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h6>Core Competencies</h6>
                    <table class="table table-bordered mb-3">
                        <thead class="table-light">
                            <tr><th>Criteria</th><th>Score (0-5)</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Knowledge of Subject</td><td>{{ $evaluation->knowledge_rating }}</td></tr>
                            <tr><td>Teaching Skill</td><td>{{ $evaluation->teaching_skill }}</td></tr>
                            <tr><td>Communication</td><td>{{ $evaluation->communication }}</td></tr>
                            <tr><td>Punctuality</td><td>{{ $evaluation->punctuality }}</td></tr>
                        </tbody>
                    </table>

                    <h6>Total Performance Score: {{ $evaluation->score }} / 40</h6>
                    <p><strong>Status:</strong> {{ ucfirst($evaluation->status) }}</p>
                    @if($evaluation->hod_comments)
                        <p><strong>HOD Comments:</strong> {{ $evaluation->hod_comments }}</p>
                    @endif

                    @if($evaluation->status === 'pending')
                        <form action="{{ route('hod.self-evaluations.approve', $evaluation->id) }}" method="POST" class="d-inline">
                            @csrf
                            <div class="mb-3">
                                <label for="hod_comments_{{ $evaluation->id }}_approve" class="form-label">HOD Comments (optional)</label>
                                <textarea name="hod_comments" id="hod_comments_{{ $evaluation->id }}_approve" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success me-2">Approve</button>
                        </form>
                        <form action="{{ route('hod.self-evaluations.reject', $evaluation->id) }}" method="POST" class="d-inline">
                            @csrf
                            <div class="mb-3">
                                <label for="hod_comments_{{ $evaluation->id }}_reject" class="form-label">HOD Comments (required for rejection)</label>
                                <textarea name="hod_comments" id="hod_comments_{{ $evaluation->id }}_reject" class="form-control" rows="2" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection