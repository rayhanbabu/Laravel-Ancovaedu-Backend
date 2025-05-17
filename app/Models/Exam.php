<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

      protected $casts = [
         'id'=>'integer',
         'exam_status'=>'integer',
         'created_by'=>'integer',
         'updated_by'=>'integer',

      ];
}
