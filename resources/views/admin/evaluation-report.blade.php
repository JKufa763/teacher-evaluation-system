@extends('layouts.admin')
@section('content')
<div class="container">
    <h2>Evaluation Report for {{ $teacher->user->name }}</h2>

    <h3>Current Performance</h3>
    <canvas id="currentPerformanceChart"></canvas>

    <h3>Weak Areas (Ratings < 3)</h3>
    @if(count($weakAreas) > 0)
        <ul>
            @foreach($weakAreas as $area => $rating)
                <li>{{ ucfirst(str_replace('_', ' ', $area)) }}: {{ round($rating, 2) }}</li>
            @endforeach
        </ul>
    @else
        <p>No weak areas identified.</p>
    @endif

    <h3>Past Performance</h3>
    <canvas id="pastPerformanceChart"></canvas>
</div>

<script>
    // Current Performance Chart (Bar Chart)
    const currentCtx = document.getElementById('currentPerformanceChart').getContext('2d');
    new Chart(currentCtx, {
        type: 'bar',
        data: {
            labels: ['Knowledge Rating', 'Teaching Skill', 'Communication', 'Punctuality'],
            datasets: [{
                label: 'Average Rating',
                data: [
                    {{ $currentPerformance['knowledge_rating'] }},
                    {{ $currentPerformance['teaching_skill'] }},
                    {{ $currentPerformance['communication'] }},
                    {{ $currentPerformance['punctuality'] }}
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
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
            labels: @json($pastPerformance->pluck('label')),
            datasets: [
                {
                    label: 'Knowledge Rating',
                    data: @json($pastPerformance->pluck('avg_knowledge')),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false
                },
                {
                    label: 'Teaching Skill',
                    data: @json($pastPerformance->pluck('avg_teaching')),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false
                },
                {
                    label: 'Communication',
                    data: @json($pastPerformance->pluck('avg_communication')),
                    borderColor: 'rgba(255, 206, 86, 1)',
                    fill: false
                },
                {
                    label: 'Punctuality',
                    data: @json($pastPerformance->pluck('avg_punctuality')),
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