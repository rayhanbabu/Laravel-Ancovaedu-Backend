<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\ExamService\ExamAdd;
use App\Services\ExamService\ExamList;
use App\Services\ExamService\ExamUpdate;
use App\Services\ExamService\ExamDelete;

class ExamController extends Controller
{

    protected $ExamAdd;
    protected $ExamList;
    protected $ExamUpdate;
    protected $ExamDelete;


    public function __construct(ExamAdd $ExamAdd, ExamList $ExamList, ExamUpdate $ExamUpdate, ExamDelete $ExamDelete)
    {
         $this->ExamAdd = $ExamAdd;
         $this->ExamList = $ExamList;
         $this->ExamUpdate = $ExamUpdate;
         $this->ExamDelete = $ExamDelete;
    }

  
     public function exam_add(Request $request,$school_username)
     {
          return $this->ExamAdd->handle($request,$school_username);
     }

     public function exam(Request $request,$school_username){
           return $this->ExamList->handle($request,$school_username);
     }

      public function exam_update(Request $request,$school_username, $id)
      {
          return $this->ExamUpdate->handle($request,$school_username,$id);
      }
   
 
       public function exam_delete(Request $request,$school_username, $id)
       {
           return $this->ExamDelete->handle($request ,$school_username , $id);
       }




}
