@extends('layouts.admin')

@section('content')
<style>
    h2 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .btn-primary {
        background-color: var(--primary-color);
        color: var(--accent-color);
        border: none;
        padding: 8px 16px;
        border-radius: 0.25rem;
    }
    .btn-primary:hover {
        background-color: #495057;
    }
    .cycle-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .cycle-status {
        color: #28a745;
        font-weight: bold;
    }
    .cycle-past {
        color: #6c757d;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
</style>

<div class="container">
    <h2>Manage Evaluation Cycles</h2>

    <!-- Error messages -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Set New Cycle -->
    <div class="card">
        <div class="card-body">
            <h3>Set New Evaluation Cycle</h3>
            <form action="{{ route('admin.cycles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cycle_name">Cycle Name (e.g., 2025-Q1)</label>
                    <input type="text" name="cycle_name" id="cycle_name" class="form-control" required>
                    @error('cycle_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Set New Cycle</button>
            </form>
        </div>
    </div>

    <!-- Current and Past Cycles -->
    <div class="card">
        <div class="card-body">
            <h3>Evaluation Cycles</h3>
            @if($cycles->isEmpty())
                <p class="empty">No cycles defined.</p>
            @else
                <ul class="list-unstyled">
                    @foreach($cycles as $cycle)
                        <li class="cycle-item">
                            <span>
                                {{ $cycle->name }}
                                @if($cycle->is_active)
                                    <span class="cycle-status">(Active)</span>
                                @else
                                    <span class="cycle-past">(Previous Cycle)</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection