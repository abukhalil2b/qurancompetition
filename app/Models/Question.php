<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function questionset()
    {
        return $this->belongsTo(Questionset::class);
    }

    public function selections()
    {
        return $this->hasMany(StudentQuestionSelection::class);
    }

    public function surat()
    {
        return $this->belongsTo(QuranSurat::class, 'quran_surat_id');
    }

    public function ayas()
    {
        return $this->hasMany(
            QuranAya::class,
            'quran_surat_id',
            'quran_surat_id'
        );
    }

}
