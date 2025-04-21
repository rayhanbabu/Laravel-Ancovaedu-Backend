<?php 
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\User_role;

  function prx($arr){
       echo "<pre>";
       print_r($arr);
       die();
  }

    function user(){
         $user=User::with('user_role')->find(Auth::id());
          return $user;
     }

   function rayhan(){
       return 'Md Rayhan Babu';
    }

?>