<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Programyear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\ProgramyearService\ProgramyearAdd;
use App\Services\ProgramyearService\ProgramyearList;
use App\Services\ProgramyearService\ProgramyearUpdate;
use App\Services\ProgramyearService\ProgramyearDelete;

class ProgramyearController extends Controller
{

    protected $ProgramyearAdd;
    protected $ProgramyearList;
    protected $ProgramyearUpdate;
    protected $ProgramyearDelete;


    public function __construct(ProgramyearAdd $ProgramyearAdd, ProgramyearList $ProgramyearList, ProgramyearUpdate $ProgramyearUpdate, ProgramyearDelete $ProgramyearDelete)
    {
         $this->ProgramyearAdd = $ProgramyearAdd;
         $this->ProgramyearList = $ProgramyearList;
         $this->ProgramyearUpdate = $ProgramyearUpdate;
         $this->ProgramyearDelete = $ProgramyearDelete;
    }

  
    
     public function programyear_add(Request $request,$school_username)
     {
          return $this->ProgramyearAdd->handle($request,$school_username);
     }


     public function programyear(Request $request,$school_username){
           return $this->ProgramyearList->handle($request,$school_username);
     }


      public function programyear_update(Request $request,$school_username, $id)
        {
          return $this->ProgramyearUpdate->handle($request,$school_username,$id);
       }
   
 
       public function programyear_delete(Request $request,$school_username, $id)
       {
           return $this->ProgramyearDelete->handle($request ,$school_username , $id);
       }




}
