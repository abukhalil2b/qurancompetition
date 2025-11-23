<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function questionset()
    {
        return $this->belongsTo(Questionset::class);
    }

    public function attempts()
    {
        return $this->hasMany(StudentQuestionAttempt::class);
    }
}
