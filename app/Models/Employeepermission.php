<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeepermission extends Model
{
    use HasFactory;

    protected $casts = [
        'id'=>'integer',
        'employee_user_id'=>'integer',
        'subject_id'=>'integer',
        'sessionyear_id'=>'integer',
        'programyear_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'section_id'=>'integer',
        'exam_id'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];


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

     public function employee_user()
      {
        return $this->belongsTo(User::class,'employee_user_id');
      }


      public function subject()
      {
          return $this->belongsTo(Subject::class,'subject_id');
      }


      public function exam()
    {
        return $this->belongsTo(Exam::class,'exam_id');
    }




}
