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
        'mark_group',
    ];


        protected $casts = [
            'id'=>'integer',
            'subject_id'=>'integer',
            'enroll_id'=>'integer',
            'exam_id'=>'integer',
            'level1_mark' => 'double:2',
            'level2_mark'=>'double:2',
            'level3_mark'=>'double:2',
            'sub_total'=>'double:2',
            'total'=>'double:2',
            'gpa'=>'double:2',
            'attendance_status'=>'integer',
            'attendance_by'=>'integer',
            'final_submit_status'=>'integer',
            'final_submited_by'=>'integer',
            'check_status'=>'integer',
            'checked_by'=>'integer',
            'created_by'=>'integer',
            'updated_by'=>'integer',
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
