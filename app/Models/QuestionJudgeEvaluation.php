<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionJudgeEvaluation extends Model
{
       protected $guarded = [];
       
       public function selected()
       {
              return $this->belongsTo(StudentQuestionSelection::class, 'student_question_selection_id');
       }

       public function judge()
       {
              return $this->belongsTo(User::class, 'judge_id');
       }
}
