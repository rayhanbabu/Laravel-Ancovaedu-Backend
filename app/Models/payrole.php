<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payrole extends Model
{
    use HasFactory;


        protected $casts = [
        'employee_id'=>'integer',
        'payroleinfo_id'=>'integer',
        'id'=>'integer',
      ];

    public function user()
    {
        return $this->hasOneThrough(User::class, Employee::class, 'id', 'id', 'employee_id', 'user_id');
    }
}
