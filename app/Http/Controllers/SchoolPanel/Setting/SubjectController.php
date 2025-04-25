<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\SubjectService\SubjectAdd;
use App\Services\SubjectService\SubjectList;
use App\Services\SubjectService\SubjectUpdate;
use App\Services\SubjectService\SubjectDelete;

class SubjectController extends Controller
{

    protected $SubjectAdd;
    protected $SubjectList;
    protected $SubjectUpdate;
    protected $SubjectDelete;

    public function __construct(SubjectAdd $SubjectAdd, SubjectList $SubjectList, SubjectUpdate $SubjectUpdate, SubjectDelete $SubjectDelete)
    {
         $this->SubjectAdd = $SubjectAdd;
         $this->SubjectList = $SubjectList;
         $this->SubjectUpdate = $SubjectUpdate;
         $this->SubjectDelete = $SubjectDelete;
    }

  
     public function subject_add(Request $request,$school_username)
     {
          return $this->SubjectAdd->handle($request,$school_username);
     }

     public function subject(Request $request,$school_username){
           return $this->SubjectList->handle($request,$school_username);
     }

      public function subject_update(Request $request,$school_username, $id)
      {
          return $this->SubjectUpdate->handle($request,$school_username,$id);
      }
   
 
       public function subject_delete(Request $request,$school_username, $id)
       {
           return $this->SubjectDelete->handle($request ,$school_username , $id);
       }




}
