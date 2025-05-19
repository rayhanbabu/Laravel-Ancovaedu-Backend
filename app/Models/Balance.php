<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

     protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'previous_id'=>'integer',
        'previous_balance'=>'integer',
        'amount'=>'integer',
        'balance'=>'integer',
        'status'=>'integer',
        'verified_by'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];


      public function creator()
       {
           return $this->belongsTo(User::class,'created_by');
       }

       public function updater()
       {
           return $this->belongsTo(User::class,'updated_by');
       }

       public function verified()
       {
           return $this->belongsTo(User::class,'verified_by');
       }
}
