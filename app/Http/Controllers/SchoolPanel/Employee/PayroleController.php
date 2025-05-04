<?php

namespace App\Http\Controllers\SchoolPanel\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\PayroleService\PayroleAdd;
use App\Services\PayroleService\PayroleList;
use App\Services\PayroleService\PayroleSingle;
use App\Services\PayroleService\PayroleDelete;


class PayroleController extends Controller
{

    protected $PayroleAdd;
    protected $PayroleList;
    protected $PayroleSingle;
    protected $PayroleDelete;


    public function __construct(PayroleAdd $PayroleAdd, PayroleList $PayroleList, PayroleSingle $PayroleSingle 
    , PayroleDelete $PayroleDelete)
    {
         $this->PayroleAdd = $PayroleAdd;
         $this->PayroleList = $PayroleList;
         $this->PayroleSingle = $PayroleSingle;
         $this->PayroleDelete = $PayroleDelete;        
    }

  
     public function payrole_add(Request $request,$school_username)
     {
          return $this->PayroleAdd->handle($request,$school_username);
     }


     public function payrole(Request $request,$school_username){
           return $this->PayroleList->handle($request,$school_username);
     }

      public function payrole_single_add(Request $request,$school_username)
      {
          return $this->PayroleSingle->handle($request,$school_username);
      }
   
 
       public function payrole_delete(Request $request,$school_username, $id)
       {
           return $this->PayroleDelete->handle($request ,$school_username , $id);
       }

      
    
}
