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

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
