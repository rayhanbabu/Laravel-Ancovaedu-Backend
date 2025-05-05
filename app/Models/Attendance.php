<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_username',
        'sessionyear_id',
        'programyear_id',
        'level_id',
        'faculty_id',
        'department_id',
        'section_id',
        'student_id',
        'classdate_id',
        'status',
    ];

    public function classdate()
    {
        return $this->belongsTo(Classdate::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
