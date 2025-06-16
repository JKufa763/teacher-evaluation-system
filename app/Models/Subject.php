<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Class_::class, 'class_subject', 'subject_id', 'class_id')
                   ->withTimestamps();
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'subject_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subject', 'subject_id', 'student_id')
                   ->withPivot('completed')
                   ->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}