<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentinvoice extends Model
  {
      use HasFactory;


         protected $casts = [
          'id'=>'integer',
          'payment_id'=>'integer',
          'invoice_id'=>'integer',
         ];

       protected $fillable = [
          'school_username',
          'payment_id',
          'invoice_id',
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
