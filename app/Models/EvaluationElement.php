<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationElement extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function judgeEvaluations()
    {
        return $this->hasMany(JudgeEvaluation::class, 'evaluation_element_id');
    }
}
