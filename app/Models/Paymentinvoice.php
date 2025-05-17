<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentinvoice extends Model
  {
      use HasFactory;


    

       protected $fillable = [
          'school_username',
          'payment_id',
          'invoice_id',
       ];


       protected $casts = [
          'id'=>'integer',
          'payment_id'=>'integer',
          'invoice_id'=>'integer',
          'created_by'=>'integer',
          'updated_by'=>'integer',
        ];

         // Payment.php
          public function invoices() {
             return $this->belongsToMany(Invoice::class,'id','payment_id');
          }

        //    // Invoice.php
        //    public function payments() {
        //        return $this->belongsToMany(Payment::class, 'payment_invoice');
        //  }
    }
