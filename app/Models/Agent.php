<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'district',
        'upazila',
        'nid_front_image',
        'nid_back_image',
        'account_name',
        'account_number',
        'bank_name',
        'branch_name',
        'swift_code',
        'routing_number',
        'bkash_number',
        'rocket_number',
        'nagad_number'
    ];

       protected $casts = [
           'id'=>'integer',
           'user_id'=>'integer',
        ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function user_role()
    {
        return $this->hasOne(User_role::class, 'user_id', 'user_id');
    }

}
