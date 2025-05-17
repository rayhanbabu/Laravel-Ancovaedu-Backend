<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

       protected $casts = [
        'id'=>'integer',
        'level_status'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];
}
