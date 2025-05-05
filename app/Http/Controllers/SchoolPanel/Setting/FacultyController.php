<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\FacultyService\FacultyAdd;
use App\Services\FacultyService\FacultyList;
use App\Services\FacultyService\FacultyUpdate;
use App\Services\FacultyService\FacultyDelete;

class FacultyController extends Controller
{

    protected $FacultyAdd;
    protected $FacultyList;
    protected $FacultyUpdate;
    protected $FacultyDelete;


    public function __construct(FacultyAdd $FacultyAdd, FacultyList $FacultyList, FacultyUpdate $FacultyUpdate, FacultyDelete $FacultyDelete)
    {
         $this->FacultyAdd = $FacultyAdd;
         $this->FacultyList = $FacultyList;
         $this->FacultyUpdate = $FacultyUpdate;
         $this->FacultyDelete = $FacultyDelete;
    }

  
     public function faculty_add(Request $request,$school_username)
     {
          return $this->FacultyAdd->handle($request,$school_username);
     }

     public function faculty(Request $request,$school_username){
           return $this->FacultyList->handle($request,$school_username);
     }

      public function faculty_update(Request $request,$school_username, $id)
      {
          return $this->FacultyUpdate->handle($request,$school_username,$id);
      }
   
 
       public function faculty_delete(Request $request,$school_username, $id)
       {
           return $this->FacultyDelete->handle($request ,$school_username , $id);
       }




}
