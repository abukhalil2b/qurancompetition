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

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function competitions()
    {
        return $this->hasMany(Competition::class);
    }

    public function judges()
    {
        return $this->belongsToMany(User::class, 'committee_users', 'committee_id', 'user_id')
            ->where('users.user_type', 'judge')
            ->withPivot('is_judge_leader')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'committee_users', 'committee_id', 'user_id')
            ->withPivot('is_judge_leader')
            ->withTimestamps();
    }

    public function leader()
    {
        return $this->hasOne(CommitteeUser::class, 'committee_id')
            ->where('is_judge_leader', true);
    }
}
