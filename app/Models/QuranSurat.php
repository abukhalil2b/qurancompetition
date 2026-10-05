<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranSurat extends Model
{
    public $timestamps = false;

    public function ayas()
    {
        return $this->hasMany(QuranAya::class, 'quran_surat_id');
    }
}
