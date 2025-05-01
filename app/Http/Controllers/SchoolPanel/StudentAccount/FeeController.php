<?php

namespace App\Http\Controllers\SchoolPanel\StudentAccount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\FeeService\FeeAdd;
use App\Services\FeeService\FeeList;
use App\Services\FeeService\FeeUpdate;
use App\Services\FeeService\FeeDelete;




class FeeController extends Controller
{

    protected $FeeAdd;
    protected $FeeList;
    protected $FeeUpdate;
    protected $FeeDelete;
   

    public function __construct(FeeAdd $FeeAdd, FeeList $FeeList, FeeUpdate $FeeUpdate,
     FeeDelete $FeeDelete)
    {
         $this->FeeAdd = $FeeAdd;
         $this->FeeList = $FeeList;
         $this->FeeUpdate = $FeeUpdate;
         $this->FeeDelete = $FeeDelete;
       
    }

  
     public function fee_add(Request $request,$school_username)
       {
           return $this->FeeAdd->handle($request,$school_username);
       }


     public function fee(Request $request,$school_username){
           return $this->FeeList->handle($request,$school_username);
     }

      public function fee_update(Request $request,$school_username, $id)
      {
          return $this->FeeUpdate->handle($request,$school_username,$id);
      }
   
 
       public function fee_delete(Request $request,$school_username, $id)
       {
           return $this->FeeDelete->handle($request ,$school_username , $id);
       }

     

}
