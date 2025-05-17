<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
            'user_id',
            'bangla_name',
            'english_name',
            'religion_id',
            'gender'
      ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'religion_id'=>'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];

}
