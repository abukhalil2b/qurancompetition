<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
        'center_id',
        'stage_id',
        'committee_id',
        'student_id',
        'level',
        'questionset_id',
        'student_status',
        'present_at',
        'with_committee_at',
        'finished_at',
        'final_score',
    ];

    protected $casts = [
        'present_at' => 'datetime',
        'with_committee_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function studentQuestionSelections()
    {
        return $this->hasMany(StudentQuestionSelection::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function questionset()
    {
        return $this->belongsTo(Questionset::class, 'questionset_id');
    }
}
