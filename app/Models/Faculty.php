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

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }



}
