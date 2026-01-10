<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TafseerResult extends Model
{
    protected $fillable = [
        'competition_id',
        'total_score',
        'done'
    ];
}

