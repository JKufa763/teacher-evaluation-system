@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Evaluation Report for {{ $reportData['teacher']->user->name ?? 'Unknown Teacher' }}</h2>
    <a href="{{ route('teacher.evaluation-report.export') }}" class="btn btn-success mb-3">Export to PDF</a>
    <div class="card mb-4">
        <div class="card-header">Overall Ratings (0-5)</div>
        <div class="card-body">
            <p><strong>Self Evaluation Rating (50%):</strong> {{ number_format($reportData['selfAvgRating'], 2) }}/5</p>
            <p><strong>Peer Evaluation Rating (30%):</strong> {{ number_format($reportData['peerAvgRating'], 2) }}/5</p>
            <p><strong>Student Evaluation Rating (20%):</strong> {{ number_format($reportData['studentAvgRating'], 2) }}/5</p>
            <p><strong>Weighted Average Rating:</strong> {{ number_format($reportData['weightedRating'], 2) }}/5</p>
            @if ($reportData['selfAvgScore'] !== null)
                <hr>
                <p><strong>Self Performance Score:</strong> {{ number_format($reportData['selfAvgScore'], 2) }}/40</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Current Performance Breakdown (0-5)</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Self</th>
                        <th>Peer</th>
                        <th>Student</th>
                        <th>Weighted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportData['currentPerformance'] as $criteria => $ratings)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $criteria)) }}</td>
                            <td>{{ number_format($ratings['self'], 2) }}</td>
                            <td>{{ number_format($ratings['peer'], 2) }}</td>
                            <td>{{ number_format($ratings['student'], 2) }}</td>
                            <td>{{ number_format($ratings['weighted'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h3>Current Performance Chart</h3>
            <canvas id="currentPerformanceChart"></canvas>
        </div>
    </div>

    @if (!empty($reportData['weakAreas']))
        <div class="card mb-4">
            <div class="card-header bg-warning">Areas for Improvement (Weighted Ratings < 3)</div>
            <div class="card-body">
                <p>Consider recommending workshops or masterclasses for the following areas:</p>
                <ul>
                    @foreach ($reportData['weakAreas'] as $criteria => $ratings)
                        <li>{{ ucwords(str_replace('_', ' ', $criteria)) }}: {{ number_format($ratings['weighted'], 2) }}/5</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-header">Areas for Improvement</div>
            <div class="card-body">
                <p>No weak areas identified.</p>
            </div>
        </div>
    @endif

    @if ($reportData['selfEvaluations']->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">Self Evaluation Details</div>
            <div class="card-body">
                @foreach ($reportData['selfEvaluationDetails'] as $index => $eval)
                    <h5>Performance Planning (Score: {{ number_format($eval['score'], 2) }}/40)</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Criteria</th>
                                <th>Target</th>
                                <th>Actual</th>
                                <th>Score (0-10)</th>
                                <th>Evidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eval['performance_planning'] as $key => $data)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                    <td>{{ $data['target'] ?? '-' }}</td>
                                    <td>{{ $data['actual_performance'] ?? '-' }}</td>
                                    <td>{{ $data['score'] ?? '-' }}</td>
                                    <td>{{ $data['evidence'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <h5>Personal Attributes</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Attribute</th>
                                <th>Rating</th>
                                <th>Recommendation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eval['personal_attributes'] as $key => $rating)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                    <td>{{ $rating }}</td>
                                    <td>
                                        @if (in_array($rating, ['requires_improvement', 'unsatisfactory']))
                                            Consider a workshop/masterclass
                                        @else
                                            None
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">Performance Trends Over Time</div>
        <div class="card-body">
            <canvas id="pastPerformanceChart"></canvas>
            <h5>Trend Details</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Knowledge (Weighted)</th>
                        <th>Teaching Skill (Weighted)</th>
                        <th>Communication (Weighted)</th>
                        <th>Punctuality (Weighted)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportData['pastPerformance'] as $trend)
                        <tr>
                            <td>{{ $trend['label'] }}</td>
                            <td>{{ number_format($trend['knowledge']['weighted'], 2) }}</td>
                            <td>{{ number_format($trend['teaching']['weighted'], 2) }}</td>
                            <td>{{ number_format($trend['communication']['weighted'], 2) }}</td>
                            <td>{{ number_format($trend['punctuality']['weighted'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Current Performance Chart (Bar Chart)
    const currentCtx = document.getElementById('currentPerformanceChart').getContext('2d');
    new Chart(currentCtx, {
        type: 'bar',
        data: {
            labels: ['Knowledge Rating', 'Teaching Skill', 'Communication', 'Punctuality'],
            datasets: [
                {
                    label: 'Self Rating',
                    data: [
                        {{ $reportData['currentPerformance']['knowledge_rating']['self'] }},
                        {{ $reportData['currentPerformance']['teaching_skill']['self'] }},
                        {{ $reportData['currentPerformance']['communication']['self'] }},
                        {{ $reportData['currentPerformance']['punctuality']['self'] }}
                    ],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Peer Rating',
                    data: [
                        {{ $reportData['currentPerformance']['knowledge_rating']['peer'] }},
                        {{ $reportData['currentPerformance']['teaching_skill']['peer'] }},
                        {{ $reportData['currentPerformance']['communication']['peer'] }},
                        {{ $reportData['currentPerformance']['punctuality']['peer'] }}
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Student Rating',
                    data: [
                        {{ $reportData['currentPerformance']['knowledge_rating']['student'] }},
                        {{ $reportData['currentPerformance']['teaching_skill']['student'] }},
                        {{ $reportData['currentPerformance']['communication']['student'] }},
                        {{ $reportData['currentPerformance']['punctuality']['student'] }}
                    ],
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Weighted Rating',
                    data: [
                        {{ $reportData['currentPerformance']['knowledge_rating']['weighted'] }},
                        {{ $reportData['currentPerformance']['teaching_skill']['weighted'] }},
                        {{ $reportData['currentPerformance']['communication']['weighted'] }},
                        {{ $reportData['currentPerformance']['punctuality']['weighted'] }}
                    ],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: { beginAtZero: true, max: 5 }
            }
        }
    });

    // Past Performance Chart (Line Chart)
    const pastCtx = document.getElementById('pastPerformanceChart').getContext('2d');
    new Chart(pastCtx, {
        type: 'line',
        data: {
            labels: @json($reportData['pastPerformance']->pluck('label')),
            datasets: [
                {
                    label: 'Knowledge Rating (Weighted)',
                    data: @json($reportData['pastPerformance']->pluck('knowledge.weighted')),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false
                },
                {
                    label: 'Teaching Skill (Weighted)',
                    data: @json($reportData['pastPerformance']->pluck('teaching.weighted')),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false
                },
                {
                    label: 'Communication (Weighted)',
                    data: @json($reportData['pastPerformance']->pluck('communication.weighted')),
                    borderColor: 'rgba(255, 206, 86, 1)',
                    fill: false
                },
                {
                    label: 'Punctuality (Weighted)',
                    data: @json($reportData['pastPerformance']->pluck('punctuality.weighted')),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                y: { beginAtZero: true, max: 5 }
            }
        }
    });
</script>
@endsection