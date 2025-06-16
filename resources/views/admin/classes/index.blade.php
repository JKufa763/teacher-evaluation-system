@extends('layouts.admin')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Class Management</h2>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Create New Class</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $class)
            <tr>
                <td>{{ $class->name }}</td>
                <td>{{ $class->grade->name }}</td>
                <td>
                    <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this class?')">Delete</button>
                        <style>
                            .delete-btn {
                                background-color: #dc3545;
                                color: white;
                                cursor: pointer;
                                padding: 0.5rem 1rem;
                                border-radius: 0.25rem;
                            }
                            .delete-btn:hover {
                                background-color: #bb2d3b;
                            }
                        </style>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection