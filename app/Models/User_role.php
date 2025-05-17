<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_role extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_type',
        'created_by',
    ];

      protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'created_by'=>'integer',
        'updated_by'=>'integer',
      ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
