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


    public function sessionyear()
    {
        return $this->belongsTo(Sessionyear::class, 'sessionyear_id');
    }

    public function programyear()
    {
        return $this->belongsTo(Programyear::class, 'programyear_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }


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
