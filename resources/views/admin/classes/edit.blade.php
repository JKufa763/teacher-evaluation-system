@extends('layouts.admin')

@section('content')
<div class="card">
    <h2>Edit Class: {{ $class->name }}</h2>
    
    <form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="name">Class Name (e.g., Grade 10A)</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $class->name }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="grade_id">Grade</label>
            <select name="grade_id" id="grade_id" class="form-control" required>
                <option value="">Select Grade</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ $class->grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                @endforeach
            </select>
            @error('grade_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Update Class</button>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection