<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable = [
        'student_id', 'evaluator_teacher_id', 'teacher_id', 'subject_id', 'class_id',
        'evaluation_type', 'evaluation_cycle_id', 'knowledge_rating', 'teaching_skill', 'communication',
        'punctuality', 'comments', 'user_id', 'hod_comments', 'completed', 'status', 'score',
    ];

    protected $casts = [
        'evaluation_type' => 'string',
        'status' => 'string',
        'completed' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(Class_::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'user_id', 'evaluator_teacher_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluationCycle()
    {
        return $this->belongsTo(EvaluationCycle::class);
    }

    public function getNormalizedScoreAttribute()
    {
        if ($this->evaluation_type === 'self') {
            return $this->score ? ($this->score / 8) : 0;
        } elseif ($this->evaluation_type === 'peer') {
            return $this->score ? ($this->score / 20) : 0;
        } elseif ($this->evaluation_type === 'student') {
            $total = ($this->knowledge_rating ?? 0) +
                     ($this->teaching_skill ?? 0) +
                     ($this->communication ?? 0) +
                     ($this->punctuality ?? 0);
            return $total ? ($total / 4) : 0;
        }
        return 0;
    }

    public function getAverageRatingAttribute()
    {
        $ratings = array_filter([
            $this->knowledge_rating,
            $this->teaching_skill,
            $this->communication,
            $this->evaluation_type !== 'self' ? $this->punctuality : null,
        ], fn($value) => !is_null($value));

        return count($ratings) ? array_sum($ratings) / count($ratings) : 0;
    }
}