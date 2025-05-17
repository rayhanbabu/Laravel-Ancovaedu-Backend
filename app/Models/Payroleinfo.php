<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroleinfo extends Model
{
    use HasFactory;

    protected $table = 'payroleinfos';
    protected $fillable = [
        'employee_id',
        'college_sallary',
        'school_username',
        'user_id',
        'basic_salary',
        'increment',
    ];

      protected $casts = [
        'employee_id'=>'integer',
        'id'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];

    public function user()
    {
        return $this->hasOneThrough(User::class, Employee::class, 'id', 'id', 'employee_id', 'user_id');
    }



}
