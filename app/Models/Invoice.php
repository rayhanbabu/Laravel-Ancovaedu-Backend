<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
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


       protected $casts = [
        'id'=>'integer',
        'enroll_id'=>'integer',
        'fee_id'=>'integer',
        'amount'=>'integer',
        'waiver_amount'=>'integer',
        'waiver_approved_status'=>'integer',
        'waiver_request_by'=>'integer',
        'waiver_approved_by'=>'integer',
        'total_amount'=>'integer',
        'payment_status'=>'integer',
        'invoice_create_status'=>'integer',
        'partial_payment'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
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
