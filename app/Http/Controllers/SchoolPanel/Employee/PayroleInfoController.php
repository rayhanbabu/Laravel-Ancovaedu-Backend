<?php

namespace App\Http\Controllers\SchoolPanel\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\PayroleinfoService\PayroleinfoAdd;
use App\Services\PayroleinfoService\PayroleinfoList;
use App\Services\PayroleinfoService\PayroleinfoUpdate;
use App\Services\PayroleinfoService\PayroleinfoDelete;


class PayroleInfoController extends Controller
{

    protected $PayroleinfoAdd;
    protected $PayroleinfoList;
    protected $PayroleinfoUpdate;
    protected $PayroleinfoDelete;


    public function __construct(PayroleinfoAdd $PayroleinfoAdd, PayroleinfoList $PayroleinfoList, PayroleinfoUpdate $PayroleinfoUpdate 
    , PayroleinfoDelete $PayroleinfoDelete)
    {
         $this->PayroleinfoAdd = $PayroleinfoAdd;
         $this->PayroleinfoList = $PayroleinfoList;
         $this->PayroleinfoUpdate = $PayroleinfoUpdate;
         $this->PayroleinfoDelete = $PayroleinfoDelete;        
    }

  
     public function payroleinfo_add(Request $request,$school_username)
     {
          return $this->PayroleinfoAdd->handle($request,$school_username);
     }


     public function payroleinfo(Request $request,$school_username){
           return $this->PayroleinfoList->handle($request,$school_username);
     }

      public function payroleinfo_update(Request $request,$school_username, $id)
      {
          return $this->PayroleinfoUpdate->handle($request,$school_username,$id);
      }
   
 
       public function payroleinfo_delete(Request $request,$school_username, $id)
       {
           return $this->PayroleinfoDelete->handle($request ,$school_username , $id);
       }

      
    
}
