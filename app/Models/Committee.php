<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $guarded = [];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'committee_students');
    }

    public function judges()
    {
        return $this->belongsToMany(User::class, 'committee_judges', 'committee_id', 'judge_id')
            ->withTimestamps();
    }
}
