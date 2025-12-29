<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'active'];

    public function committeeUsers()
    {
        return $this->hasMany(CommitteeUser::class);
    }

    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'committee_users')
            ->using(CommitteeUser::class)
            ->withPivot('stage_id')
            ->withTimestamps();
    }
}
