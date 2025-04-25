<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\SessionService\SessionAdd;
use App\Services\SessionService\SessionList;
use App\Services\SessionService\SessionUpdate;
use App\Services\SessionService\SessionDelete;

class SessionController extends Controller
{

    protected $SessionAdd;
    protected $SessionList;
    protected $SessionUpdate;
    protected $SessionDelete;


    public function __construct(SessionAdd $SessionAdd, SessionList $SessionList, SessionUpdate $SessionUpdate, SessionDelete $SessionDelete)
    {
         $this->SessionAdd = $SessionAdd;
         $this->SessionList = $SessionList;
         $this->SessionUpdate = $SessionUpdate;
         $this->SessionDelete = $SessionDelete;
    }

  
     public function session_add(Request $request,$school_username)
     {
          return $this->SessionAdd->handle($request,$school_username);
     }

     public function session(Request $request,$school_username){
           return $this->SessionList->handle($request,$school_username);
     }

      public function session_update(Request $request,$school_username, $id)
      {
          return $this->SessionUpdate->handle($request,$school_username,$id);
      }
   
 
       public function session_delete(Request $request,$school_username, $id)
       {
           return $this->SessionDelete->handle($request ,$school_username , $id);
       }




}
