<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }


       protected $casts = [
          'id'=>'integer',
          'faculty_id'=>'integer',
       ];

}
