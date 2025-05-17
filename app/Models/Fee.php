<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
  
        'school_username',
        'department_id',
        'level_id',
        'session_id',
        'programyear_id',
        'section_id',
        'faculty_id',
        'created_by',
    ];

     protected $casts = [
        'id'=>'integer',
        'sessionyear_id'=>'integer',
        'programyear_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'section_id'=>'integer',
        'amount'=>'integer',
        'invoice_create_status'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];

}
