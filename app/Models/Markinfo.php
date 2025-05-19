<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markinfo extends Model
{
    use HasFactory;

      protected $casts = [
            'id'=>'integer',
            'sessionyear_id'=>'integer',
            'programyear_id'=>'integer',
            'level_id'=>'integer',
            'faculty_id'=>'integer',
            'department_id' => 'integer',
            'section_id'=>'integer',
            'subject_id'=>'integer',
            'enroll_id'=>'integer',
            'exam_id'=>'integer',
            'start'=>'double:2',
            'end'=>'double:2',
            'gpa'=>'double:2',
            'gparange'=>'double:2',
            'created_by'=>'integer',
            'updated_by'=>'integer',
      ];

}
