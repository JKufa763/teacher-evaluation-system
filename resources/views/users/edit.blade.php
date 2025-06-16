@extends('layouts.app')

@section('content')
    <h1>Edit User</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" required>
        </div>
        <div>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                <option value="hod" {{ $user->role === 'hod' ? 'selected' : '' }}>HOD</option>
                <option value="deputy_head" {{ $user->role === 'deputy_head' ? 'selected' : '' }}>Deputy Head</option>
            </select>
        </div>
        <div id="subject-field" style="display: {{ $user->role === 'teacher' || $user->role === 'hod' ? 'block' : 'none' }};">
            <label for="subject_id">Subject:</label>
            <select name="subject_id" id="subject_id">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $user->subject_id === $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Update User</button>
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