<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationElement extends Model
{
     public $timestamps = false;
     
    protected $guarded = [];

    public function childElements()
    {
        return $this->hasMany(EvaluationElement::class, 'parent_id')->orderBy('order');
    }

    public function parentElement()
    {
        return $this->belongsTo(EvaluationElement::class, 'parent_id');
    }

    public function judgeEvaluations()
{
    return $this->hasMany(JudgeEvaluation::class, 'evaluation_element_id');
}

}
