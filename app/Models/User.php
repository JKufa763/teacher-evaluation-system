<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role', // Values: admin, deputy_head, hod, teacher, student
        'subject_id',
        'department_id' // Added for HOD functionality
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role Check Methods
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'deputy_head']);
    }

    public function isHod()
    {
        return $this->role === 'hod' || 
               Department::where('hod_id', $this->id)->exists();
    }

    public function isTeacher()
    {
        return $this->role === 'teacher' || $this->isHod();
    }

    // Relationships
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supervisedDepartment()
    {
        return $this->hasOne(Department::class, 'hod_id');
    }

    public function supervisedTeachers()
    {
        if (!$this->isHod()) return collect();
        
        return User::where('department_id', $this->department_id)
                 ->where('role', 'teacher')
                 ->where('id', '!=', $this->id)
                 ->get();
    }

    public function classes()
    {
        if($this->isTeacher()) {
            return $this->belongsToMany(Class_::class, 'class_teacher', 'teacher_id', 'class_id');
        }
        return $this->belongsToMany(Class_::class, 'student_class', 'student_id', 'class_id');
        
    }

    // Maintain existing relationships with type hints
    public function teacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function hod(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Hod::class);
    }

    public function deputyHead(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DeputyHead::class);
    }

    // New evaluation relationships
    public function evaluationsGiven()
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    public function evaluationsReceived()
    {
        return $this->hasMany(Evaluation::class, 'teacher_id');

    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class,);
    }

    public function studentEvvaluations()
    {
        return $this->hasManyThrough(
            Evaluation::class,
            Subject::class,
            'teacher_id', // Foreign key on subjects table
            'subject_id', // Foreign key on evaluations table
            'id', // Local key on users table
            'id' // Local key on subjects table
        )->where('evaluations.evalaluator_type', 'student');
    }

    public function peerEvaluations()
    {
        return $this->hasManyThrough(
            Evaluation::class,
            Subject::class,
            'teacher_id', // Foreign key on subjects table
            'subject_id', // Foreign key on evaluations table
            'id', // Local key on users table
            'id' // Local key on subjects table
        )->where('evaluations.evalaluator_type', 'peer');
    }
}