<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'opt_code',
        'profile_picture',
        'email_verified_at',
        'remember_token',
        'first_phone',
        'last_phone',
        'username',
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
     protected $casts = [
         'email_verified_at' => 'datetime',
         'password' => 'hashed',
         'status'=>'integer',
         'opt_code'=>'integer',
      ];

    public function user_role(){
         return $this->hasOne(User_role::class,'user_id');
     }

      public function agent(){
         return $this->hasOne(School::class,'agent_user_id');
      }

      public function school(){
          return $this->hasOne(School::class,'user_id');
      }

       public function employee(){
           return $this->hasOne(Employee::class,'user_id');
       }

       public function student(){
           return $this->hasOne(Student::class,'user_id');
       }

        public function permissions(){
             return $this->hasMany(Employeepermission::class,'employee_user_id');
         }

   
}
