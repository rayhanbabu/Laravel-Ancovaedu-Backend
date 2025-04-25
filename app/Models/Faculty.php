<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_username',
        'faculty_name',
        'status',
        'created_by',
        'updated_by',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_username', 'username');
    }



}
