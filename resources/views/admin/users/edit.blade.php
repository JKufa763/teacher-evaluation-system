@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <h2 style="font-size: 24px; font-weight: 600; color: #4a5568; margin-bottom: 20px;">Edit User: {{ $user->name }}</h2>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <!-- Name Field -->
            <div>
                <label for="name" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                       style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);">
                @error('name')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email Field -->
            <div>
                <label for="email" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                       style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);">
                @error('email')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Field -->
            <div>
                <label for="password" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">New Password (leave blank to keep current)</label>
                <input type="password" name="password" id="password"
                       style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);">
                @error('password')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Confirm Password Field -->
            <div>
                <label for="password_confirmation" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);">
            </div>
            
            <!-- Role Field -->
            <div style="grid-column: span 2;">
                <label for="role" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Role</label>
                <select name="role" id="role" style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);">
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Teacher/HOD Fields -->
            <div style="grid-column: span 2; display: none;" id="teacher-hod-fields">
                <label for="subject_id" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Subject (for Teachers/HODs)</label>
                <select name="subject_id" id="subject_id" style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $user->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror

                <label for="department_id" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Department (for Teachers/HODs)</label>
                <select name="department_id" id="department_id" style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Teacher Class Field -->
            <div style="grid-column: span 2; display: none;" id="teacher-class-field">
                <label for="teacher_class_ids" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Classes (for Teachers, optional)</label>
                <select name="class_ids[]" id="teacher_class_ids" multiple style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;">
                    <option value="">No Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $user->teacher && $user->teacher->classes->contains($class->id) ? 'selected' : '' }}>
                            {{ $class->name }} ({{ $class->grade->name }})
                        </option>
                    @endforeach
                </select>
                <small style="color: #4a5568;">Hold Ctrl (Windows) or Command (Mac) to select multiple classes.</small>
                @error('class_ids')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Student Class Field -->
            <div style="grid-column: span 2; display: none;" id="student-class-field">
                <label for="student_class_id" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Class (for Students)</label>
                <select name="class_id" id="student_class_id" style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;" required>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $user->student ? $user->student->classes()->first()->id ?? '' : '') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} ({{ $class->grade->name }})
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Student Subjects Field -->
            <div style="grid-column: span 2; display: none;" id="student-subjects-field">
                <label for="subject_ids" style="font-weight: 500; color: #4a5568; margin-bottom: 4px; display: block;">Subjects (Select 4 for Students)</label>
                <select name="subject_ids[]" id="subject_ids" multiple style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;" required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $user->student && $user->student->subjects->contains($subject->id) ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
                <small style="color: #4a5568;">Hold Ctrl (Windows) or Command (Mac) to select multiple subjects.</small>
                @error('subject_ids')
                    <p style="color: #e53e3e; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.users.index') }}" style="padding: 8px 12px; border: 1px solid #cbd5e0; border-radius: 4px; color: #4a5568; background-color: #ffffff; text-decoration: none; transition: background-color 0.2s;">
                Cancel
            </a>
            <button type="submit" style="padding: 8px 12px; border: none; border-radius: 4px; color: #ffffff; background-color: #4c51bf; cursor: pointer; transition: background-color 0.2s;">
                Update User
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const teacherHodFields = document.getElementById('teacher-hod-fields');
        const teacherClassField = document.getElementById('teacher-class-field');
        const studentClassField = document.getElementById('student-class-field');
        const studentSubjectsField = document.getElementById('student-subjects-field');

        function updateFields() {
            teacherHodFields.style.display = 'none';
            teacherClassField.style.display = 'none';
            studentClassField.style.display = 'none';
            studentSubjectsField.style.display = 'none';

            if (roleSelect.value === 'teacher' || roleSelect.value === 'hod') {
                teacherHodFields.style.display = 'block';
            }
            if (roleSelect.value === 'teacher') {
                teacherClassField.style.display = 'block';
            }
            if (roleSelect.value === 'student') {
                studentClassField.style.display = 'block';
                studentSubjectsField.style.display = 'block';
            }
        }

        updateFields();
        roleSelect.addEventListener('change', updateFields);
    });
</script>
@endsection