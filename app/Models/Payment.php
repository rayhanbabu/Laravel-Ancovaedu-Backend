<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;


   protected $casts = [
        'id'=>'integer',
        'enroll_id'=>'integer',
        'payment_status'=>'integer',
        'amount'=>'integer',
        'year'=>'integer',
        'month'=>'integer',
        'day'=>'integer',
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
    

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'paymentinvoices', 'payment_id', 'invoice_id');
    }
   
}
