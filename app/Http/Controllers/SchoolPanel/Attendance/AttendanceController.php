<?php

namespace App\Http\Controllers\SchoolPanel\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\AttendanceService\AttendanceAdd;
use App\Services\AttendanceService\AttendanceList;
use App\Services\AttendanceService\AttendanceUpdate;
use App\Services\AttendanceService\AttendanceDelete;




class AttendanceController extends Controller
{

    protected $AttendanceAdd;
    protected $AttendanceList;
    protected $AttendanceUpdate;
    protected $AttendanceDelete;
   

    public function __construct(AttendanceAdd $AttendanceAdd, AttendanceList $AttendanceList, AttendanceUpdate $AttendanceUpdate,
     AttendanceDelete $AttendanceDelete)
    {
         $this->AttendanceAdd = $AttendanceAdd;
         $this->AttendanceList = $AttendanceList;
         $this->AttendanceUpdate = $AttendanceUpdate;
         $this->AttendanceDelete = $AttendanceDelete;
       
    }

  
     public function attendance_add(Request $request,$school_username)
       {
           return $this->AttendanceAdd->handle($request,$school_username);
       }


     public function attendance(Request $request,$school_username){
           return $this->AttendanceList->handle($request,$school_username);
     }

      public function attendance_update(Request $request,$school_username)
      {
          return $this->AttendanceUpdate->handle($request,$school_username);
      }
   
 
       public function attendance_delete(Request $request,$school_username, $id)
       {
           return $this->AttendanceDelete->handle($request ,$school_username , $id);
       }

     

}
