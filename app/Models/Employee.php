<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'bangla_name',
        'english_name',
        'designation_id',
        'user_id',
        'school_username',
        'level_id',
        'faculty_id',
        'department_id',
        'school_username',
        'gender',
        'relationship',
        'blood_group',
        'religion_id',
        'joining_date',
        'index_number',
        'tin_number',
        'dob',
        'father_name',
        'mother_name',
        'spouse_name',
        'present_address',
        'permanent_address',
        'created_by',
        'updated_by',
    ];


     protected $casts = [
        'id'=>'integer',
        'user_id'=>'integer',
        'religion_id'=>'integer',
        'designation_id'=>'integer',
        'level_id'=>'integer',
        'faculty_id'=>'integer',
        'department_id' => 'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    
        
}
