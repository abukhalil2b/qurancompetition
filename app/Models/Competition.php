<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
   protected $guarded = [];
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
