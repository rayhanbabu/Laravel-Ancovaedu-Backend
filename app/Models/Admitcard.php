<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admitcard extends Model
{
    use HasFactory;


    protected $fillable = [
        'student_id',
        'school_id',
        'class_id',
        'section_id',
        'created_by',
        'updated_by'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
