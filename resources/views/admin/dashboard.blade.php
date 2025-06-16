@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Admin Dashboard</h1>
    

    <!-- Current Evaluation Cycle -->
    <div class="card">
        <h3>Current Evaluation Cycle</h3>
        <p>{{ $currentCycle ? $currentCycle->name : 'No active cycle' }} | <a href="{{ route('admin.cycles') }}">Manage Cycles</a></p>
    </div>
    

    <!-- Statistics -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-label">Total Users</span>
                    <span class="stat-value">{{ $stats['total_users'] ?? 0 }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Teachers</span>
                    <span class="stat-value">{{ $stats['active_teachers'] ?? 0 }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Departments</span>
                    <span class="stat-value">{{ $stats['total_departments'] ?? 0 }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Students</span>
                    <span class="stat-value">{{ $stats['total_students'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-row {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            flex-wrap: wrap;
            gap: 15px;
            padding: 10px 0;
        }
        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #333;
            position: relative;
            padding: 0 15px;
            flex: 1;
            min-width: 100px;
        }
        .stat-item:not(:last-child)::after {
            content: '|';
            position: absolute;
            right: -2px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-weight: bold;
        }
        .stat-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
            text-align: center;
        }
        .stat-value {
            font-weight: bold;
            color: #007bff;
            font-size: 1.2rem;
            text-align: center;
        }
        @media (max-width: 768px) {
            .stats-row {
                flex-direction: column;
                align-items: stretch;
            }
            .stat-item {
                padding: 10px 0;
                flex: none;
                min-width: auto;
            }
            .stat-item:not(:last-child)::after {
                display: none;
            }
        }
    </style>

    <!-- Charts -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Statistics Overview</div>
                <div class="card-body">
                    <canvas id="statsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Department Breakdown</div>
                <div class="card-body">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div class="mt-4">
        <a href="{{ route('admin.classes.index') }}" class="btn btn-primary">Manage Classes</a>
        <a href="{{ route('admin.reports') }}" class="btn btn-primary">View Reports</a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Statistics Overview Chart (Bar Chart)
        const statsCtx = document.getElementById('statsChart').getContext('2d');
        new Chart(statsCtx, {
            type: 'bar',
            data: {
                labels: ['Total Users', 'Teachers', 'Departments', 'Students'],
                datasets: [{
                    label: 'Counts',
                    data: [
                        {{ $stats['total_users'] ?? 0 }},
                        {{ $stats['active_teachers'] ?? 0 }},
                        {{ $stats['total_departments'] ?? 0 }},
                        {{ $stats['total_students'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(40, 167, 69, 0.2)',
                        'rgba(23, 162, 184, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Department Breakdown Chart (Pie Chart)
        const deptCtx = document.getElementById('departmentChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'pie',
            data: {
                labels: @json($departments->pluck('name')),
                datasets: [
                    {
                        label: 'Teachers per Department',
                        data: @json($departments->pluck('teachers_count')),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: 'Students per Department',
                        data: @json($departments->pluck('students_count')),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.4)',
                            'rgba(54, 162, 235, 0.4)',
                            'rgba(255, 206, 86, 0.4)',
                            'rgba(75, 192, 192, 0.4)',
                            'rgba(153, 102, 255, 0.4)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    </script>
</div>
@endsection