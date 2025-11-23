<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'date',
    ];

    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'committee_students', 'student_id', 'committee_id');
    }

    public function questionAttempts()
    {
        return $this->hasMany(StudentQuestionAttempt::class);
    }
}
