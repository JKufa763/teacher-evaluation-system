<!DOCTYPE html>
<html>
<head>
    <title>Department Evaluation Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2, h3, h4 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h2>Department Evaluation Report - {{ $department->name }}</h2>
    <p><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p>

    <h3>Department Summary</h3>
    <table>
        <tr><th>Metric</th><th>Value</th></tr>
        <tr><td>Average Self Rating (out of 5)</td><td>{{ number_format($departmentSummary['avgSelfRating'], 2) }}</td></tr>
        <tr><td>Average Peer Rating (out of 5)</td><td>{{ number_format($departmentSummary['avgPeerRating'], 2) }}</td></tr>
        <tr><td>Average Student Rating (out of 5)</td><td>{{ number_format($departmentSummary['avgStudentRating'], 2) }}</td></tr>
        <tr><td>Average Self Score (out of 40)</td><td>{{ number_format($departmentSummary['avgSelfScore'], 2) }}</td></tr>
        <tr><td>Average Weighted Rating (out of 5)</td><td>{{ number_format($departmentSummary['avgWeightedRating'], 2) }}</td></tr>
    </table>

    <div class="page-break"></div>

    <h3>Teacher Reports</h3>
    @foreach($teacherReports as $teacherId => $report)
        <h4>Teacher: {{ $report['teacher']->user->name ?? 'Unknown' }} ({{ $report['teacher']->department->name ?? 'No Department' }})</h4>
        <table>
            <tr><th>Metric</th><th>Value</th></tr>
            <tr><td>Self Evaluation Rating (50%)</td><td>{{ number_format($report['selfAvgRating'], 2) }}/5</td></tr>
            <tr><td>Peer Evaluation Rating (30%)</td><td>{{ number_format($report['peerAvgRating'], 2) }}/5</td></tr>
            <tr><td>Student Evaluation Rating (20%)</td><td>{{ number_format($report['studentAvgRating'], 2) }}/5</td></tr>
            <tr><td>Weighted Average Rating</td><td>{{ number_format($report['weightedRating'], 2) }}/5</td></tr>
            @if($report['selfAvgScore'] !== null)
                <tr><td>Self Performance Score</td><td>{{ number_format($report['selfAvgScore'], 2) }}/40</td></tr>
            @endif
        </table>

        <h4>Current Performance Breakdown</h4>
        <table>
            <tr><th>Criteria</th><th>Self</th><th>Peer</th><th>Student</th><th>Weighted</th></tr>
            @foreach($report['currentPerformance'] as $criterion => $ratings)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $criterion)) }}</td>
                    <td>{{ number_format($ratings['self'], 2) }}</td>
                    <td>{{ number_format($ratings['peer'], 2) }}</td>
                    <td>{{ number_format($ratings['student'], 2) }}</td>
                    <td>{{ number_format($ratings['weighted'], 2) }}</td>
                </tr>
            @endforeach
        </table>

        @if(!empty($report['weakAreas']))
            <h4>Areas for Improvement (Weighted Rating < 3)</h4>
            <p>Consider recommending workshops or masterclasses for the following areas:</p>
            <table>
                <tr><th>Criteria</th><th>Weighted Rating</th></tr>
                @foreach($report['weakAreas'] as $criterion => $ratings)
                    <tr>
                        <td>{{ ucwords(str_replace('_', ' ', $criterion)) }}</td>
                        <td>{{ number_format($ratings['weighted'], 2) }}/5</td>
                    </tr>
                @endforeach
            </table>
        @else
            <h4>Areas for Improvement</h4>
            <p>No weak areas identified.</p>
        @endif

        <div class="page-break"></div>
    @endforeach
</body>
</html>