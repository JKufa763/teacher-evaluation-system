@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Teacher Self Evaluation Form</h2>

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
    @elseif($evaluation && $evaluation->status !== 'rejected')
        <!-- Display read-only form -->
        <div class="alert alert-info">
            Self-evaluation submitted. Status: <strong>{{ ucfirst($evaluation->status) }}</strong>
            @if($evaluation->status === 'pending')
                (Awaiting HOD approval)
            @endif
        </div>
        @php
            $comments = json_decode($evaluation->comments, true);
        @endphp
        <!-- Personal Details -->
        <div class="card mb-4 border-secondary">
            <div class="card-header bg-light text-dark fw-bold">Personal Details</div>
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->role ?? 'Teacher' }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Department ID</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->department_id ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">User ID</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->id }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core Competencies -->
        <div class="card mb-4 border-secondary">
            <div class="card-header bg-light text-dark fw-bold">Core Competencies</div>
            <div class="card-body bg-white">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Criteria</th><th>Self-Assessed Score (0-5)</th></tr>
                    </thead>
                    <tbody>
                        @php
                            $coreCompetencies = [
                                'knowledge_rating' => ['label' => 'Knowledge of Subject', 'value' => $evaluation->knowledge_rating],
                                'teaching_skill' => ['label' => 'Teaching Skill', 'value' => $evaluation->teaching_skill],
                                'communication' => ['label' => 'Communication', 'value' => $evaluation->communication],
                                'punctuality' => ['label' => 'Punctuality', 'value' => $evaluation->punctuality],
                            ];
                        @endphp
                        @foreach ($coreCompetencies as $key => $data)
                            <tr>
                                <td>{{ $data['label'] }}</td>
                                <td><input type="number" class="form-control" value="{{ $data['value'] }}" readonly></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Performance Planning -->
        <div class="card mb-4 border-secondary">
            <div class="card-header bg-light text-dark fw-bold">Performance Planning (Total: {{ $evaluation->score }}/40)</div>
            <div class="card-body bg-white">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Criteria</th><th>Target</th><th>Actual Performance</th><th>Self-Assessed Score (0-10)</th><th>Evidence</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($comments['performance_planning'] as $key => $data)
                            <tr>
                                <td>{{ str_replace('_', ' ', ucwords($key)) }}</td>
                                <td><input type="number" class="form-control" value="{{ $data['target'] }}" readonly></td>
                                <td><input type="number" class="form-control" value="{{ $data['actual_performance'] }}" readonly></td>
                                <td><input type="number" class="form-control" value="{{ $data['score'] }}" readonly></td>
                                <td><input type="text" class="form-control" value="{{ $data['evidence'] }}" readonly></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Personal Attributes -->
        <div class="card mb-4 border-secondary">
            <div class="card-header bg-light text-dark fw-bold">Personal Attributes</div>
            <div class="card-body bg-white">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Attribute</th><th>Rating</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($comments['personal_attributes'] as $key => $rating)
                            <tr>
                                <td>{{ str_replace('_', ' ', ucwords($key)) }}</td>
                                <td><input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $rating)) }}" readonly></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Show editable form -->
        @if($evaluation && $evaluation->status === 'rejected')
            <div class="alert alert-danger">
                Self-evaluation rejected by HOD. Please revise and resubmit.
                @if($evaluation->hod_comments)
                    <p><strong>HOD Comments:</strong> {{ $evaluation->hod_comments }}</p>
                @endif
            </div>
        @endif
        <form method="POST" action="{{ route('teacher.self-evaluate.store') }}" id="selfEvaluationForm">
            @csrf
            <!-- Personal Details -->
            <div class="card mb-4 border-secondary">
                <div class="card-header bg-light text-dark fw-bold">Personal Details</div>
                <div class="card-body bg-white">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->role ?? 'Teacher' }}" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Department ID</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->department_id ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">User ID</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->id }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Core Competencies -->
            <div class="card mb-4 border-secondary">
                <div class="card-header bg-light text-dark fw-bold">Core Competencies (Score out of 5 per criterion)</div>
                <div class="card-body bg-white">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr><th>Criteria</th><th>Self-Assessed Score (0-5)</th></tr>
                        </thead>
                        <tbody>
                            @php
                                $coreCompetencies = [
                                    'knowledge_rating' => ['label' => 'Knowledge of Subject', 'value' => $evaluation->knowledge_rating ?? ''],
                                    'teaching_skill' => ['label' => 'Teaching Skill', 'value' => $evaluation->teaching_skill ?? ''],
                                    'communication' => ['label' => 'Communication', 'value' => $evaluation->communication ?? ''],
                                    'punctuality' => ['label' => 'Punctuality', 'value' => $evaluation->punctuality ?? ''],
                                ];
                            @endphp
                            @foreach ($coreCompetencies as $key => $data)
                                <tr>
                                    <td>{{ $data['label'] }}</td>
                                    <td>
                                        <input type="number" name="core_competencies[{{ $key }}]" class="form-control competency-score" min="0" max="5" step="1" value="{{ $data['value'] }}" required>
                                        @error("core_competencies.$key")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <label class="form-label fw-bold">Average Competency Score (out of 5):</label>
                        <input type="text" id="averageCompetencyScore" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <!-- Performance Planning -->
            <div class="card mb-4 border-secondary">
                <div class="card-header bg-light text-dark fw-bold">Performance Planning (Score out of 10 per criterion, Total out of 40)</div>
                <div class="card-body bg-white">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr><th>Criteria</th><th>Target</th><th>Actual Performance</th><th>Self-Assessed Score (0-10)</th><th>Evidence</th></tr>
                        </thead>
                        <tbody>
                            @php
                                $performanceCriteria = [
                                    'lessons_delivered' => ['label' => 'Lessons Delivered', 'target' => 500],
                                    'exercises_tests' => ['label' => 'Exercises and Tests Administered', 'target' => 700],
                                    'records_maintained' => ['label' => 'Records Maintained', 'target' => 8],
                                    'sporting_sessions' => ['label' => 'Sporting Sessions Conducted', 'target' => 20],
                                ];
                                $existingPlanning = $evaluation ? json_decode($evaluation->comments, true)['performance_planning'] ?? [] : [];
                            @endphp
                            @foreach ($performanceCriteria as $key => $criteria)
                                <tr>
                                    <td>{{ $criteria['label'] }}</td>
                                    <td><input type="number" class="form-control" value="{{ $criteria['target'] }}" readonly></td>
                                    <td>
                                        <input type="number" name="performance_planning[{{ $key }}][actual_performance]" class="form-control" min="0" max="{{ $criteria['target'] }}" value="{{ $existingPlanning[$key]['actual_performance'] ?? '' }}" required>
                                        @error("performance_planning.$key.actual_performance")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" name="performance_planning[{{ $key }}][score]" class="form-control performance-score" min="0" max="10" step="1" value="{{ $existingPlanning[$key]['score'] ?? '' }}" required>
                                        @error("performance_planning.$key.score")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" name="performance_planning[{{ $key }}][evidence]" class="form-control" value="{{ $existingPlanning[$key]['evidence'] ?? '' }}" required>
                                        @error("performance_planning.$key.evidence")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <label class="form-label fw-bold">Total Performance Score (out of 40):</label>
                        <input type="text" id="totalScore" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <!-- Personal Attributes -->
            <div class="card mb-4 border-secondary">
                <div class="card-header bg-light text-dark fw-bold">Personal Attributes</div>
                <div class="card-body bg-white">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr><th>Attribute</th><th>Rating</th></tr>
                        </thead>
                        <tbody>
                            @php
                                $personalAttributes = [
                                    'technical_skills' => 'Technical Skills',
                                    'knowledge_of_standards' => 'Knowledge of Standards',
                                    'creativity_innovation' => 'Creativity and Innovation',
                                    'ethics' => 'Ethics',
                                    'teamwork' => 'Teamwork',
                                    'communication_skills' => 'Communication Skills',
                                    'interpersonal_relations' => 'Interpersonal Relations',
                                    'judgement_decision_making' => 'Judgement and Decision Making Skills',
                                    'leadership_management' => 'Leadership and Management',
                                ];
                                $ratings = [
                                    'excellent' => 'Excellent (5)',
                                    'very_good' => 'Very Good (4)',
                                    'satisfactory' => 'Satisfactory (3)',
                                    'requires_improvement' => 'Requires Improvement (2)',
                                    'unsatisfactory' => 'Unsatisfactory (1)',
                                ];
                                $existingAttributes = $evaluation ? json_decode($evaluation->comments, true)['personal_attributes'] ?? [] : [];
                            @endphp
                            @foreach ($personalAttributes as $key => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>
                                        <select name="personal_attributes[{{ $key }}]" class="form-control" required>
                                            @foreach ($ratings as $value => $text)
                                                <option value="{{ $value }}" {{ ($existingAttributes[$key] ?? '') === $value ? 'selected' : '' }}>{{ $text }}</option>
                                            @endforeach
                                        </select>
                                        @error("personal_attributes.$key")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    {{ $evaluation && $evaluation->status === 'rejected' ? 'Resubmit Self Evaluation' : 'Submit Self Evaluation' }}
                </button>
            </div>
        </form>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scoreInputs = document.querySelectorAll('.performance-score');
        const totalScoreInput = document.getElementById('totalScore');
        const competencyInputs = document.querySelectorAll('.competency-score');
        const averageCompetencyScoreInput = document.getElementById('averageCompetencyScore');

        function calculateTotalScore() {
            let total = 0;
            scoreInputs.forEach(input => {
                const value = parseInt(input.value) || 0;
                total += value;
            });
            totalScoreInput.value = total;
        }

        function calculateAverageCompetencyScore() {
            let total = 0;
            let count = 0;
            competencyInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
                count++;
            });
            const average = count > 0 ? (total / count).toFixed(2) : 0;
            averageCompetencyScoreInput.value = average;
        }

        scoreInputs.forEach(input => input.addEventListener('input', calculateTotalScore));
        competencyInputs.forEach(input => input.addEventListener('input', calculateAverageCompetencyScore));

        calculateTotalScore();
        calculateAverageCompetencyScore();
    });
</script>
@endsection