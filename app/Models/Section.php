<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

       protected $casts = [
        'id'=>'integer',
        'department_id'=>'integer',
        'section_status'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
