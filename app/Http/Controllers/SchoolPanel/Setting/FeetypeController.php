<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feetype;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\FeetypeService\FeetypeAdd;
use App\Services\FeetypeService\FeetypeList;
use App\Services\FeetypeService\FeetypeUpdate;
use App\Services\FeetypeService\FeetypeDelete;

class FeetypeController extends Controller
{

    protected $FeetypeAdd;
    protected $FeetypeList;
    protected $FeetypeUpdate;
    protected $FeetypeDelete;


    public function __construct(FeetypeAdd $FeetypeAdd, FeetypeList $FeetypeList, FeetypeUpdate $FeetypeUpdate, FeetypeDelete $FeetypeDelete)
    {
         $this->FeetypeAdd = $FeetypeAdd;
         $this->FeetypeList = $FeetypeList;
         $this->FeetypeUpdate = $FeetypeUpdate;
         $this->FeetypeDelete = $FeetypeDelete;
    }

  
     public function feetype_add(Request $request,$school_username)
     {
          return $this->FeetypeAdd->handle($request,$school_username);
     }

     public function feetype(Request $request,$school_username){
           return $this->FeetypeList->handle($request,$school_username);
     }

      public function feetype_update(Request $request,$school_username, $id)
      {
          return $this->FeetypeUpdate->handle($request,$school_username,$id);
      }


       public function feetype_delete(Request $request,$school_username, $id)
       {
           return $this->FeetypeDelete->handle($request ,$school_username , $id);
       }




}
