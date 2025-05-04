<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\DesignationService\DesignationAdd;
use App\Services\DesignationService\DesignationList;
use App\Services\DesignationService\DesignationUpdate;
use App\Services\DesignationService\DesignationDelete;

class DesignationController extends Controller
{

    protected $DesignationAdd;
    protected $DesignationList;
    protected $DesignationUpdate;
    protected $DesignationDelete;


    public function __construct(DesignationAdd $DesignationAdd, DesignationList $DesignationList, DesignationUpdate $DesignationUpdate, DesignationDelete $DesignationDelete)
    {
         $this->DesignationAdd = $DesignationAdd;
         $this->DesignationList = $DesignationList;
         $this->DesignationUpdate = $DesignationUpdate;
         $this->DesignationDelete = $DesignationDelete;
    }

  
     public function designation_add(Request $request,$school_username)
     {
          return $this->DesignationAdd->handle($request,$school_username);
     }

     public function designation(Request $request,$school_username){
           return $this->DesignationList->handle($request,$school_username);
     }

      public function designation_update(Request $request,$school_username, $id)
      {
          return $this->DesignationUpdate->handle($request,$school_username,$id);
      }
   
 
       public function designation_delete(Request $request,$school_username, $id)
       {
           return $this->DesignationDelete->handle($request ,$school_username , $id);
       }




}
