<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'bangla_name',
        'english_name',
        'full_address',
        'short_address',
        'eiin',
        'bangla_name_front_size',
        'english_name_front_size',
        'full_address_front_size',
        'short_address_front_size'
    ];


       protected $casts = [
          'id'=>'integer',
          'user_id'=>'integer',
          'agent_user_id'=>'integer',
        ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }
}
