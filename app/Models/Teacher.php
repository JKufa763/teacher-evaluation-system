<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model {
    protected $fillable = ['user_id', 'department_id', 'subject_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function classes() {
        return $this->belongsToMany(Class_::class, 'teacher_class', 'teacher_id', 'class_id')
                    ->withTimestamps();
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
                   
    }

    public function evaluationsReceived() {
        return $this->hasMany(Evaluation::class);
    }

    public function evaluations()
{
    return $this->hasMany(Evaluation::class);
}
}
