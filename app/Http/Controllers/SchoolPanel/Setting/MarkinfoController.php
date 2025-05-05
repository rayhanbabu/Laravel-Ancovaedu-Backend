<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\MarkinfoService\MarkinfoAdd;
use App\Services\MarkinfoService\MarkinfoList;
use App\Services\MarkinfoService\MarkinfoUpdate;
use App\Services\MarkinfoService\MarkinfoDelete;




class MarkinfoController extends Controller
{

    protected $MarkinfoAdd;
    protected $MarkinfoList;
    protected $MarkinfoUpdate;
    protected $MarkinfoDelete;
   

    public function __construct(MarkinfoAdd $MarkinfoAdd, MarkinfoList $MarkinfoList, MarkinfoUpdate $MarkinfoUpdate,
     MarkinfoDelete $MarkinfoDelete)
    {
         $this->MarkinfoAdd = $MarkinfoAdd;
         $this->MarkinfoList = $MarkinfoList;
         $this->MarkinfoUpdate = $MarkinfoUpdate;
         $this->MarkinfoDelete = $MarkinfoDelete;
       
    }

  
     public function markinfo_add(Request $request,$school_username)
       {
           return $this->MarkinfoAdd->handle($request,$school_username);
       }


     public function markinfo(Request $request,$school_username){
           return $this->MarkinfoList->handle($request,$school_username);
     }

      public function markinfo_update(Request $request,$school_username, $id)
      {
          return $this->MarkinfoUpdate->handle($request,$school_username,$id);
      }
   
 
       public function markinfo_delete(Request $request,$school_username, $id)
       {
           return $this->MarkinfoDelete->handle($request ,$school_username , $id);
       }

     

}
