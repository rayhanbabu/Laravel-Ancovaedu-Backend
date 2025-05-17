<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'subject_name',
        'subject_code',
        'level_id',
        'faculty_id',
        'department_id',
        'section_id',
        'session_id',
        'programyear_id',
    ];

      protected $casts = [
        'id' => 'integer',
        'user_id'=>'integer',
        'sessionyear_id'=>'integer',
        'programyear_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'section_id'=>'integer',
        'gpa_calculation'=>'integer',
        'serial'=>'integer',
        'input_number1'=>'integer',
        'input_number2'=>'integer',
        'input_number3'=>'integer',
        'total_number'=>'integer',
        'pass_number1'=>'integer',
        'pass_number2'=>'integer',
        'pass_number3'=>'integer',
        'religion_id '=>'integer',
        'combined_subject_id'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];


    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
