<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TafseerEvaluation extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(TafseerQuestion::class, 'tafseer_question_id');
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }
}
