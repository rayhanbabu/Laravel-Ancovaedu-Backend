<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finalresult extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_username',
        'enroll_id',
        'finalresult_group',
        'exam_id',
        'total_subject',
        'total_subject_passed',
        'total_subject_failed',
        'total_mark',
        'gpa_total',
        'gpa',
        'grade',
        'merit_position',
        'total_attendance',
        
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


     public function enroll()
    {
        return $this->belongsTo(Enroll::class);
    }

      public function exam()
    {
        return $this->belongsTo(Exam::class);
    }


     public function student()
    {
        return $this->hasOneThrough(Student::class, Enroll::class, 'id', 'id', 'enroll_id', 'student_id');
    }


}
