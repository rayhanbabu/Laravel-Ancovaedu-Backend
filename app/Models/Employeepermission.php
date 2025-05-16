<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeepermission extends Model
{
    use HasFactory;

    protected $casts = [
        'employee_user_id'=>'integer',
        'sessionyear_id'=>'integer',
        'programyear_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'section_id'=>'integer',
        'exam_id'=>'integer',
        'subject_id'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];

}
