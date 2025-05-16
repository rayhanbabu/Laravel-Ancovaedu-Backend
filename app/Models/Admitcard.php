<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admitcard extends Model
{
    use HasFactory;


    protected $fillable = [
        'student_id',
        'school_username',
        'class_id',
        'section_id',
        'created_by',
        'updated_by'
    ];


     protected $casts = [
        'sessionyear_id'=>'integer',
        'programyear_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'section_id'=>'integer',
        'subject_id'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
