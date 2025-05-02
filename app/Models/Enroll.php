<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
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
        'user_id',
        'roll',
        'created_by',
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
