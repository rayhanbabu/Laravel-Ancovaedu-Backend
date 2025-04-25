<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\ReligionService\ReligionAdd;
use App\Services\ReligionService\ReligionList;
use App\Services\ReligionService\ReligionUpdate;
use App\Services\ReligionService\ReligionDelete;

class ReligionController extends Controller
{

    protected $ReligionAdd;
    protected $ReligionList;
    protected $ReligionUpdate;
    protected $ReligionDelete;


    public function __construct(ReligionAdd $ReligionAdd, ReligionList $ReligionList, ReligionUpdate $ReligionUpdate, ReligionDelete $ReligionDelete)
    {
         $this->ReligionAdd = $ReligionAdd;
         $this->ReligionList = $ReligionList;
         $this->ReligionUpdate = $ReligionUpdate;
         $this->ReligionDelete = $ReligionDelete;
    }

  
     public function religion_add(Request $request,$school_username)
     {
          return $this->ReligionAdd->handle($request,$school_username);
     }

     public function religion(Request $request,$school_username){
           return $this->ReligionList->handle($request,$school_username);
     }

      public function religion_update(Request $request,$school_username, $id)
      {
          return $this->ReligionUpdate->handle($request,$school_username,$id);
      }
   
 
       public function religion_delete(Request $request,$school_username, $id)
       {
           return $this->ReligionDelete->handle($request ,$school_username , $id);
       }




}
