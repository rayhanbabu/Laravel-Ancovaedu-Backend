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

        protected $casts = [
          'student_id'=>'integer',
          'classdate_id'=>'integer',
          'id'=>'integer',
          'status'=>'integer',
          'created_by'=>'integer',
          'updated_by'=>'integer',
         ];

    public function classdate()
    {
        return $this->belongsTo(Classdate::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

      public function enroll()
      {
        return $this->hasOneThrough(Enroll::class, Classdate::class, 'id', 'id', 'classdate_id', 'enroll_id');
      }
}
