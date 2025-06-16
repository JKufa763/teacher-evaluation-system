<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Evaluation Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2, h3, h4 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
        .section { margin-bottom: 30px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Teacher Evaluation Report</h2>
        <p><strong>Teacher:</strong> {{ $reportData['teacher']->user->name ?? 'Unknown' }}</p>
        <p><strong>Department:</strong> {{ $reportData['teacher']->department->name ?? 'No Department' }}</p>
        <p><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p>
    </div>

    <div class="section">
        <h3>Overall Ratings</h3>
        <table>
            <tr><th>Metric</th><th>Value</th></tr>
            <tr><td>Self Evaluation Rating (50%)</td><td>{{ number_format($reportData['selfAvgRating'], 2) }}/5</td></tr>
            <tr><td>Peer Evaluation Rating (30%)</td><td>{{ number_format($reportData['peerAvgRating'], 2) }}/5</td></tr>
            <tr><td>Student Evaluation Rating (20%)</td><td>{{ number_format($reportData['studentAvgRating'], 2) }}/5</td></tr>
            <tr><td>Weighted Average Rating</td><td>{{ number_format($reportData['weightedRating'], 2) }}/5</td></tr>
            @if($reportData['selfAvgScore'] !== null)
                <tr><td>Self Performance Score</td><td>{{ number_format($reportData['selfAvgScore'], 2) }}/40</td></tr>
            @endif
        </table>
    </div>

    <div class="section">
        <h3>Current Performance Breakdown</h3>
        <table>
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
                @foreach($reportData['currentPerformance'] as $criterion => $ratings)
                    <tr>
                        <td>{{ ucwords(str_replace('_', ' ', $criterion)) }}</td>
                        <td>{{ number_format($ratings['self'], 2) }}</td>
                        <td>{{ number_format($ratings['peer'], 2) }}</td>
                        <td>{{ number_format($ratings['student'], 2) }}</td>
                        <td>{{ number_format($ratings['weighted'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        @if(!empty($reportData['weakAreas']))
            <h3>Areas for Improvement (Weighted Rating < 3)</h3>
            <p>Consider recommending workshops or masterclasses for the following areas:</p>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Weighted Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['weakAreas'] as $criterion => $ratings)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $criterion)) }}</td>
                            <td>{{ number_format($ratings['weighted'], 2) }}/5</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h3>Areas for Improvement</h3>
            <p>No weak areas identified.</p>
        @endif
    </div>

    @if($reportData['selfEvaluationDetails']->isNotEmpty())
        <div class="section">
            <h3>Self-Evaluation Details</h3>
            @foreach($reportData['selfEvaluationDetails'] as $eval)
                <h4>Performance Planning (Score: {{ number_format($eval['score'], 2) }}/40)</h4>
                <table>
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
                        @foreach($eval['performance_planning'] as $key => $data)
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

                <h4>Personal Attributes</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Attribute</th>
                            <th>Rating</th>
                            <th>Recommendation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eval['personal_attributes'] as $key => $rating)
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
        <div class="page-break"></div>
    @endif

    <div class="section">
        <h3>Performance Trends Over Time</h3>
        <table>
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
                @foreach($reportData['pastPerformance'] as $trend)
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
</body>
</html>