<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classdate extends Model
{
    use HasFactory;


       protected $casts = [
        'subject_id'=>'integer',
        'enroll_id'=>'integer',
        'id'=>'integer',
      ];

     // Payment.php
     public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    public function sessionyear()
    {
        return $this->belongsTo(Sessionyear::class, 'sessionyear_id');
    }

    public function programyear()
    {
        return $this->belongsTo(Programyear::class, 'programyear_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }


     public function enroll()
    {
        return $this->belongsTo(Enroll::class);
    }
}
