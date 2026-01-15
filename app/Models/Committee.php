<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function judges()
    {
        return $this->belongsToMany(User::class, 'committee_users', 'committee_id', 'user_id')
            ->withPivot('role', 'is_judge_leader', 'stage_id') 
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'committee_users')
            ->withPivot('stage_id')
            ->withTimestamps();
    }

    public function leaderForStage($stageId)
    {
        return $this->hasOne(CommitteeUser::class)
            ->where('stage_id', $stageId)
            ->where('is_judge_leader', true);
    }
    
}
