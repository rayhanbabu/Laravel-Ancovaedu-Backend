<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_username',
        'department_id',
        'level_id',
        'sessionyear_id',
        'programyear_id',
        'section_id',
        'faculty_id',
        'student_id',
        'exam_id',
        'subject_id',
        'created_by',
    ];

}
