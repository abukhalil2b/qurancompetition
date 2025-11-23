<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudgeEvaluation extends Model
{
    protected $guarded = [];

    public function attempt()
    {
        return $this->belongsTo(StudentQuestionAttempt::class, 'student_question_attempt_id');
    }

    public function element()
    {
        return $this->belongsTo(EvaluationElement::class, 'evaluation_element_id');
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }
}
