<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    protected $guarded = [];

    public $timestamps = false;
    
    public function committees()
    {
        return $this->hasMany(Committee::class);
    }
}
