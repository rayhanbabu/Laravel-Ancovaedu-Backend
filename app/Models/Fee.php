<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
  
        'school_username',
        'department_id',
        'level_id',
        'session_id',
        'programyear_id',
        'section_id',
        'faculty_id',
        'created_by',
    ];

}
