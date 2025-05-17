<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'school_username',
        'department_id',
        'level_id',
        'sessionyear_id',
        'programyear_id',
        'section_id',
        'faculty_id',
        'student_id',
        'user_id',
        'roll',
        'created_by',
        'created_type',
        'confirm_enroll_status',
        'subject_create_status',
        'updated_by',
        'subject_created_by',
        'enroll_group',
    ];


       protected $casts = [
        'id'=>'integer',
        'user_id'=>'integer',
        'student_id'=>'integer',
        'religion_id'=>'integer',
        'sessionyear_id'=>'integer',
        'programyear_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'section_id'=>'integer',
        'confirm_enroll_status'=>'integer',
        'roll'=>'integer',
        'subject_create_status'=>'integer',
        'subject_created_by'=>'integer',

         'main_subject1'=>'integer',
         'main_subject2'=>'integer',
         'main_subject3'=>'integer',
         'additional_subject'=>'integer',


        'confirm_enroll_by'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
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

    public function religion()
    {
        return $this->hasOneThrough(
            Religion::class,
            Student::class,
            'id',            // local key on Student table
            'id',            // local key on Religion table
            'student_id',    // foreign key on Enroll table
            'religion_id'    // foreign key on Student table
        );
    }


}
