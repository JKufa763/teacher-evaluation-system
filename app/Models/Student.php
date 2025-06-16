<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Class_::class, 'student_class', 'student_id', 'class_id')
                   ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id')
                   ->withPivot('completed', 'user_id')
                   ->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    // Optional: Get grade via class relationship
    public function grade()
    {
        return $this->hasOneThrough(Grade::class, Class_::class, 'id', 'id', 'class_id', 'grade_id')
                   ->via('classes');
    }
}