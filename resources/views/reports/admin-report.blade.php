@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">System Admin Reports</h2>
    <a href="{{ route('admin.reports.export') }}" class="btn btn-success mb-3">Export to PDF</a>

    <h3>All Teachers</h3>
    @foreach($teacherReports as $teacherId => $report)
        <div class="card mb-4">
            <div class="card-header">{{ $report['teacher']->user->name ?? 'Unknown Teacher' }} ({{ $report['teacher']->department->name ?? 'No Department' }})</div>
            <div class="card-body">
                <p><strong>Self Evaluation Rating (50%):</strong> {{ number_format($report['selfAvgRating'], 2) }}/5</p>
                <p><strong>Peer Evaluation Rating (30%):</strong> {{ number_format($report['peerAvgRating'], 2) }}/5</p>
                <p><strong>Student Evaluation Rating (20%):</strong> {{ number_format($report['studentAvgRating'], 2) }}/5</p>
                <p><strong>Weighted Average Rating:</strong> {{ number_format($report['weightedRating'], 2) }}/5</p>
                @if ($report['selfAvgScore'] !== null)
                    <hr>
                    <p><strong>Self Performance Score:</strong> {{ number_format($report['selfAvgScore'], 2) }}/40</p>
                @endif

                <h4>Current Performance Chart</h4>
                <canvas id="currentPerformanceChart_{{ $teacherId }}"></canvas>

                @if (!empty($report['weakAreas']))
                    <h4>Areas for Improvement (Weighted Ratings < 3)</h4>
                    <p>Consider recommending workshops or masterclasses for the following areas:</p>
                    <ul>
                        @foreach ($report['weakAreas'] as $criteria => $ratings)
                            <li>{{ ucwords(str_replace('_', ' ', $criteria)) }}: {{ number_format($ratings['weighted'], 2) }}/5</li>
                        @endforeach
                    </ul>
                @else
                    <h4>Areas for Improvement</h4>
                    <p>No weak areas identified.</p>
                @endif

                @if ($report['selfEvaluations']->isNotEmpty())
                    <h4>Self Evaluation Details</h4>
                    @foreach ($report['selfEvaluationDetails'] as $index => $eval)
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
                @endif

                <h4>Performance Trends Over Time</h4>
                <canvas id="pastPerformanceChart_{{ $teacherId }}"></canvas>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        <script>
            const currentCtx_{{ $teacherId }} = document.getElementById('currentPerformanceChart_{{ $teacherId }}').getContext('2d');
            new Chart(currentCtx_{{ $teacherId }}, {
                type: 'bar',
                data: {
                    labels: ['Knowledge Rating', 'Teaching Skill', 'Communication', 'Punctuality'],
                    datasets: [
                        {
                            label: 'Self Rating',
                            data: [
                                {{ $report['currentPerformance']['knowledge_rating']['self'] }},
                                {{ $report['currentPerformance']['teaching_skill']['self'] }},
                                {{ $report['currentPerformance']['communication']['self'] }},
                                {{ $report['currentPerformance']['punctuality']['self'] }}
                            ],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Peer Rating',
                            data: [
                                {{ $report['currentPerformance']['knowledge_rating']['peer'] }},
                                {{ $report['currentPerformance']['teaching_skill']['peer'] }},
                                {{ $report['currentPerformance']['communication']['peer'] }},
                                {{ $report['currentPerformance']['punctuality']['peer'] }}
                            ],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Student Rating',
                            data: [
                                {{ $report['currentPerformance']['knowledge_rating']['student'] }},
                                {{ $report['currentPerformance']['teaching_skill']['student'] }},
                                {{ $report['currentPerformance']['communication']['student'] }},
                                {{ $report['currentPerformance']['punctuality']['student'] }}
                            ],
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Weighted Rating',
                            data: [
                                {{ $report['currentPerformance']['knowledge_rating']['weighted'] }},
                                {{ $report['currentPerformance']['teaching_skill']['weighted'] }},
                                {{ $report['currentPerformance']['communication']['weighted'] }},
                                {{ $report['currentPerformance']['punctuality']['weighted'] }}
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

            const pastCtx_{{ $teacherId }} = document.getElementById('pastPerformanceChart_{{ $teacherId }}').getContext('2d');
            new Chart(pastCtx_{{ $teacherId }}, {
                type: 'line',
                data: {
                    labels: @json($report['pastPerformance']->pluck('label')),
                    datasets: [
                        {
                            label: 'Knowledge Rating (Weighted)',
                            data: @json($report['pastPerformance']->pluck('knowledge.weighted')),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            fill: false
                        },
                        {
                            label: 'Teaching Skill (Weighted)',
                            data: @json($report['pastPerformance']->pluck('teaching.weighted')),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            fill: false
                        },
                        {
                            label: 'Communication (Weighted)',
                            data: @json($report['pastPerformance']->pluck('communication.weighted')),
                            borderColor: 'rgba(255, 206, 86, 1)',
                            fill: false
                        },
                        {
                            label: 'Punctuality (Weighted)',
                            data: @json($report['pastPerformance']->pluck('punctuality.weighted')),
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
    @endforeach

    <h3>All Departments</h3>
    @foreach($departmentSummaries as $deptId => $summary)
        <div class="card mb-4">
            <div class="card-header">{{ $summary['department']->name ?? 'Unknown Department' }}</div>
            <div class="card-body">
                <p><strong>Average Self Rating:</strong> {{ number_format($summary['avgSelfRating'], 2) }}/5</p>
                <p><strong>Average Peer Rating:</strong> {{ number_format($summary['avgPeerRating'], 2) }}/5</p>
                <p><strong>Average Student Rating:</strong> {{ number_format($summary['avgStudentRating'], 2) }}/5</p>
                <p><strong>Average Weighted Rating:</strong> {{ number_format($summary['avgWeightedRating'], 2) }}/5</p>
                @if ($summary['avgSelfScore'] !== null)
                    <hr>
                    <p><strong>Average Self Performance Score:</strong> {{ number_format($summary['avgSelfScore'], 2) }}/40</p>
                @endif

                <h4>Department Average Chart</h4>
                <canvas id="departmentSummaryChart_{{ $deptId }}"></canvas>
            </div>
        </div>

        <script>
            const deptCtx_{{ $deptId }} = document.getElementById('departmentSummaryChart_{{ $deptId }}').getContext('2d');
            new Chart(deptCtx_{{ $deptId }}, {
                type: 'bar',
                data: {
                    labels: [
                        'Self Rating',
                        'Peer Rating',
                        'Student Rating',
                        'Weighted Rating',
                        @if ($summary['avgSelfScore'] !== null)
                            'Self Score'
                        @endif
                    ],
                    datasets: [{
                        label: 'Department Average',
                        data: [
                            {{ $summary['avgSelfRating'] }},
                            {{ $summary['avgPeerRating'] }},
                            {{ $summary['avgStudentRating'] }},
                            {{ $summary['avgWeightedRating'] }},
                            @if ($summary['avgSelfScore'] !== null)
                                {{ $summary['avgSelfScore'] }}
                            @endif
                        ],
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true, max: 40 }
                    }
                }
            });
        </script>
    @endforeach

    <h3>School-Wide Average</h3>
    <div class="card mb-4">
        <div class="card-header">School Averages (0-5)</div>
        <div class="card-body">
            <p><strong>Average Self Rating:</strong> {{ number_format($schoolAverage['avgSelfRating'], 2) }}/5</p>
            <p><strong>Average Peer Rating:</strong> {{ number_format($schoolAverage['avgPeerRating'], 2) }}/5</p>
            <p><strong>Average Student Rating:</strong> {{ number_format($schoolAverage['avgStudentRating'], 2) }}/5</p>
            <p><strong>Average Weighted Rating:</strong> {{ number_format($schoolAverage['avgWeightedRating'], 2) }}/5</p>
            @if ($schoolAverage['avgSelfScore'] !== null)
                <hr>
                <p><strong>Average Self Performance Score:</strong> {{ number_format($schoolAverage['avgSelfScore'], 2) }}/40</p>
            @endif

            <h4>School-Wide Average Chart</h4>
            <canvas id="schoolAverageChart"></canvas>
        </div>
    </div>

    <a href="{{ route('admin.reports.export') }}" class="btn btn-primary">Export to PDF for Ministry</a>

    <script>
        const schoolCtx = document.getElementById('schoolAverageChart').getContext('2d');
        new Chart(schoolCtx, {
            type: 'bar',
            data: {
                labels: [
                    'Self Rating',
                    'Peer Rating',
                    'Student Rating',
                    'Weighted Rating',
                    @if ($schoolAverage['avgSelfScore'] !== null)
                        'Self Score'
                    @endif
                ],
                datasets: [{
                    label: 'School-Wide Average',
                    data: [
                        {{ $schoolAverage['avgSelfRating'] }},
                        {{ $schoolAverage['avgPeerRating'] }},
                        {{ $schoolAverage['avgStudentRating'] }},
                        {{ $schoolAverage['avgWeightedRating'] }},
                        @if ($schoolAverage['avgSelfScore'] !== null)
                            {{ $schoolAverage['avgSelfScore'] }}
                        @endif
                    ],
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, max: 40 }
                }
            }
        });
    </script>
</div>
@endsection