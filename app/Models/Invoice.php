<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_username',
        'session_id',
        'programyear_id',
        'level_id',
        'faculty_id',
        'department_id',
        'section_id',
        'fee_id',
        'amount',
        'status',
        'student_id',
    ];


    public function enroll()
    {
        return $this->belongsTo(Enroll::class);
    }


    public function student()
    {
        return $this->hasOneThrough(Student::class, Enroll::class, 'id', 'id', 'enroll_id', 'student_id');
    }
    

}
