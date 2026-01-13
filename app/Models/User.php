<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
	// use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'name',
		'gender',
		'national_id',
		'user_type',
		'password',
		'plain_password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function committees()
	{
		return $this->belongsToMany(Committee::class, 'committee_users', 'user_id', 'committee_id')
			->withPivot('stage_id', 'is_judge_leader')
			->withTimestamps();
	}

	public function isCommitteeLeader($stageId)
    {
        // If the user is a global admin, they should have leader powers
        if ($this->user_type === 'admin') {
            return true;
        }

        return $this->committees()
            ->wherePivot('stage_id', $stageId)
            ->wherePivot('is_judge_leader', true)
            ->exists();
    }
	
	
}
