<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TafseerQuestion extends Model
{
    protected $guarded = [];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    // ✅ Add this relationship
    public function evaluations()
    {
        return $this->hasMany(TafseerEvaluation::class);
    }
}

