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
		return $this->belongsToMany(Committee::class, 'committee_judges', 'judge_id', 'committee_id')
			->withTimestamps();
	}
}
