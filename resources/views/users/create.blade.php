@extends('layouts.app')

@section('content')
    <h1>Create New User</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
                <option value="hod">HOD</option>
                <option value="deputy_head">Deputy Head</option>
            </select>
        </div>
        <div id="subject-field" style="display: none;">
            <label for="subject_id">Subject:</label>
            <select name="subject_id" id="subject_id">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Create User</button>
    </form>

    <script>
        // Show/hide subject field based on role
        document.getElementById('role').addEventListener('change', function () {
            const subjectField = document.getElementById('subject-field');
            if (this.value === 'teacher' || this.value === 'hod') {
                subjectField.style.display = 'block';
            } else {
                subjectField.style.display = 'none';
            }
        });
    </script>
@endsection