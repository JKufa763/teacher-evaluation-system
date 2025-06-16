@extends('layouts.admin')

@section('content')
<div class="card">
    <h2>Create New Class</h2>
    
    <form method="POST" action="{{ route('admin.classes.store') }}">
        @csrf
    
        <div class="form-group">
            <label for="name">Class Name (e.g., Grade 10A)</label>
            <input type="text" class="form-control" id="name" name="name" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="grade_id">Grade</label>
            <select name="grade_id" id="grade_id" class="form-control" required>
                <option value="">Select Grade</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
            @error('grade_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Create Class</button>
    </form>
</div>
@endsection