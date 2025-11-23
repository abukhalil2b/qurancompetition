<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuestionAttempt extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function questionset()
    {
        return $this->belongsTo(Questionset::class);
    }

    public function judgeEvaluations()
    {
        return $this->hasMany(JudgeEvaluation::class);
    }
}
