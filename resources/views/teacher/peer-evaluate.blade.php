@extends('layouts.app')

@section('content')
<style>
    .teacher-card {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border-left: 5px solid #007bff;
        display: none;
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
    .info-message {
        background-color: #e7f3fe;
        color: #31708f;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    .error {
        color: #dc3545;
        font-size: 0.875em;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4">Peer Evaluation</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(!$currentCycle)
        <div class="alert alert-warning">
            No active evaluation cycle. Please contact the administrator.
        </div>
    @elseif($peers->isEmpty())
        <div class="alert alert-warning">
            No other teachers available to evaluate in this cycle.
        </div>
    @else
        <div class="info-message">
            <p>In this phase, you can evaluate other teachers based on their attributes (except yourself) to help the school progress toward a more conducive learning environment. We hope every evaluation is taken seriously and that you provide an objective perspective. Select a teacher to begin.</p>
        </div>

        <form method="POST" action="{{ route('teacher.peer-evaluate.store') }}">
            @csrf

            <div class="mb-3">
                <label for="teacher_id" class="form-label">Select Teacher to Evaluate</label>
                <select name="teacher_id" id="teacher_id" class="form-control" required onchange="updateTeacherDetails()">
                    <option value="">-- Select a Teacher --</option>
                    @foreach($peers as $peer)
                        <option value="{{ $peer->id }}"
                                data-name="{{ $peer->user->name }}"
                                data-department="{{ $peer->department->name ?? 'Not assigned' }}"
                                data-role="{{ $peer->user->role }}">
                            {{ $peer->user->name }} ({{ $peer->department->name ?? 'No Department' }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="teacher-card" id="teacher-details">
                <h3>Teacher Details</h3>
                <p><strong>Name:</strong> <span id="teacher-name"></span></p>
                <p><strong>Department:</strong> <span id="teacher-department"></span></p>
                <p><strong>Role:</strong> <span id="teacher-role"></span></p>
            </div>

            <div class="card mb-4">
                <div class="card-header">Evaluation Ratings (1-5)</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="knowledge_rating" class="form-label">Knowledge of Standards</label>
                        <input type="number" name="knowledge_rating" id="knowledge_rating" class="form-control" min="1" max="5" step="1" required>
                        @error('knowledge_rating')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="teaching_skill" class="form-label">Teaching Skill</label>
                        <input type="number" name="teaching_skill" id="teaching_skill" class="form-control" min="1" max="5" step="1" required>
                        @error('teaching_skill')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="communication" class="form-label">Communication</label>
                        <input type="number" name="communication" id="communication" class="form-control" min="1" max="5" step="1" required>
                        @error('communication')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="punctuality" class="form-label">Punctuality</label>
                        <input type="number" name="punctuality" id="punctuality" class="form-control" min="1" max="5" step="1" required>
                        @error('punctuality')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="comments" class="form-label">Comments</label>
                        <textarea name="comments" id="comments" class="form-control" rows="3"></textarea>
                        @error('comments')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit Peer Evaluation</button>
        </form>

        <script>
            function updateTeacherDetails() {
                const select = document.getElementById('teacher_id');
                const teacherDetails = document.getElementById('teacher-details');
                const teacherName = document.getElementById('teacher-name');
                const teacherDepartment = document.getElementById('teacher-department');
                const teacherRole = document.getElementById('teacher-role');

                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption.value) {
                    teacherDetails.style.display = 'block';
                    teacherName.textContent = selectedOption.dataset.name;
                    teacherDepartment.textContent = selectedOption.dataset.department;
                    teacherRole.textContent = selectedOption.dataset.role;
                } else {
                    teacherDetails.style.display = 'none';
                }
            }
        </script>
    @endif
</div>
@endsection