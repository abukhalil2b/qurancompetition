<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranAya extends Model
{
    public $timestamps = false;

    public function surat()
    {
        return $this->belongsTo(QuranSurat::class, 'quran_surat_id');
    }
}
