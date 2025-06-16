@extends('layouts.admin')

@section('content')
<div class="card">
    <h2>Create New User</h2>
    
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
    
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="Leave blank to auto-generate">
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('role')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group" id="teacher-hod-fields" style="display: none;">
            <label for="subject_id">Subject (for Teachers/HODs)</label>
            <select name="subject_id" id="subject_id" class="form-control">
                <option value="">Select Subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
            @error('subject_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            <label for="department_id">Department (for Teachers/HODs)</label>
            <select name="department_id" id="department_id" class="form-control">
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
            @error('department_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="teacher-class-field" style="display: none;">
            <label for="teacher_class_ids">Classes (for Teachers, optional)</label>
            <select name="class_ids[]" id="teacher_class_ids" class="form-control" multiple>
                <option value="">No Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->grade->name }})</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple classes.</small>
            @error('class_ids')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="student-class-field" style="display: none;">
            <label for="student_class_id">Class (for Students)</label>
            <select name="class_id" id="student_class_id" class="form-control" required>
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->grade->name }})</option>
                @endforeach
            </select>
            @error('class_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="student-subjects-field" style="display: none;">
            <label for="subject_ids">Subjects (Select 4 for Students)</label>
            <select name="subject_ids[]" id="subject_ids" class="form-control" multiple required>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple subjects.</small>
            @error('subject_ids')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        const teacherHodFields = document.getElementById('teacher-hod-fields');
        const teacherClassField = document.getElementById('teacher-class-field');
        const studentClassField = document.getElementById('student-class-field');
        const studentSubjectsField = document.getElementById('student-subjects-field');

        teacherHodFields.style.display = 'none';
        teacherClassField.style.display = 'none';
        studentClassField.style.display = 'none';
        studentSubjectsField.style.display = 'none';

        if (this.value === 'teacher' || this.value === 'hod') {
            teacherHodFields.style.display = 'block';
        }
        if (this.value === 'teacher') {
            teacherClassField.style.display = 'block';
        }
        if (this.value === 'student') {
            studentClassField.style.display = 'block';
            studentSubjectsField.style.display = 'block';
        }
    });
</script>
<!-- Add at the bottom of the script tag -->
<script>
    document.getElementById('role').addEventListener('change', function() {
        const teacherHodFields = document.getElementById('teacher-hod-fields');
        const teacherClassField = document.getElementById('teacher-class-field');
        const studentClassField = document.getElementById('student-class-field');
        const studentSubjectsField = document.getElementById('student-subjects-field');

        teacherHodFields.style.display = 'none';
        teacherClassField.style.display = 'none';
        studentClassField.style.display = 'none';
        studentSubjectsField.style.display = 'none';

        if (this.value === 'teacher' || this.value === 'hod') {
            teacherHodFields.style.display = 'block';
            document.getElementById('subject_id').required = true;
            document.getElementById('department_id').required = true;
        } else {
            document.getElementById('subject_id').required = false;
            document.getElementById('department_id').required = false;
        }
        if (this.value === 'teacher') {
            teacherClassField.style.display = 'block';
        }
        if (this.value === 'student') {
            studentClassField.style.display = 'block';
            studentSubjectsField.style.display = 'block';
            document.getElementById('student_class_id').required = true;
            document.getElementById('subject_ids').required = true;
        } else {
            document.getElementById('student_class_id').required = false;
            document.getElementById('subject_ids').required = false;
        }
    });

    // Add form submission debugging
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('Form submission attempted');
        console.log('Form data:', new FormData(this));
        // Uncomment to prevent submission for testing
        // e.preventDefault();
    });
</script>
@endsection