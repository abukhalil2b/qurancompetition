<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuestionSelection extends Model
{
    protected $guarded = [];

    protected $casts = [
        'done' => 'boolean',
    ];

    public function isFinished()
    {
        return $this->done === true;
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function judgeEvaluations()
    {
        return $this->hasMany(JudgeEvaluation::class);
    }

    public function totalDeduction(): float
    {
        return $this->judgeEvaluations()->sum('reduct_point');
    }

    public function judgeNotes()
    {
        return $this->hasMany(JudgeNote::class);
    }
}
