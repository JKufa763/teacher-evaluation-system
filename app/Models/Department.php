<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Databse\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'hod_id'];

    public function hod(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }
    
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

}
