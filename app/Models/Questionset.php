<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionset extends Model
{
     public $timestamps = false;
     protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'questionset_students');
    }
}
