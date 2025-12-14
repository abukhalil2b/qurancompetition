<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuestionSelection extends Model
{
    protected $guarded = [];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }


    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
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
