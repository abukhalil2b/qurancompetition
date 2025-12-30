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

    public function selections()
    {
        return $this->hasMany(StudentQuestionSelection::class);
    }

    public function getLevelClassAttribute()
    {
        return match ($this->difficulties) {
            'القوية' => 'hard',
            'المتوسطة' => 'medium',
            'السهلة' => 'easy',
            default => 'easy',
        };
    }
}
