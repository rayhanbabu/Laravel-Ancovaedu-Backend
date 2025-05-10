<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_username',
        'enroll_id',
        'exam_id',
        'subject_id',
        'created_by',
        'final_submit_status',
        'final_submited_by',
    ];


    public function enroll()
    {
        return $this->belongsTo(Enroll::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function student()
    {
        return $this->hasOneThrough(Student::class, Enroll::class, 'id', 'id', 'enroll_id', 'student_id');
    }

}
