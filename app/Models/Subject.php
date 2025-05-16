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
        'user_id'=>'integer',
        'sessionyear_id'=>'integer',
        'programyear_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'section_id'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];


    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
