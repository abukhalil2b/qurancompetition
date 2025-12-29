<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeUser extends Model
{
    protected $table = 'committee_users';

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}

