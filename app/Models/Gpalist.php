<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gpalist extends Model
{
    use HasFactory;

    protected $table = 'gpalists';

    protected $fillable = [
        'session_year',
        'school_username',
        'gpa_category_id',
        'status',
        'total_student',
        'total_pass',
        'total_fail',
        'pass_rate',
        'gpa5',
        'gpa4',
        'gpa3',
        'gpa35',
        'gpa2',
        'gpa1',
        'gpa0',
        'created_by',
        'updated_by'
    ];

    public function gpaCategory()
    {
        return $this->belongsTo(Gpacategory::class, 'gpa_category_id');
    }
}
