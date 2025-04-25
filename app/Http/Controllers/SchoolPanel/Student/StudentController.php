<?php

namespace App\Http\Controllers\SchoolPanel\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\StudentService\StudentAdd;
use App\Services\StudentService\StudentList;
use App\Services\StudentService\StudentUpdate;
use App\Services\StudentService\StudentDelete;
use App\Services\StudentService\StudentTransfer;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;

class StudentController extends Controller
{

    protected $StudentAdd;
    protected $StudentList;
    protected $StudentUpdate;
    protected $StudentDelete;
    protected $StudentTransfer;


    public function __construct(StudentAdd $StudentAdd, StudentList $StudentList, StudentUpdate $StudentUpdate,
     StudentDelete $StudentDelete, StudentTransfer $StudentTransfer)
    {
         $this->StudentAdd = $StudentAdd;
         $this->StudentList = $StudentList;
         $this->StudentUpdate = $StudentUpdate;
         $this->StudentDelete = $StudentDelete;
         $this->StudentTransfer = $StudentTransfer;
    }

  
     public function student_add(Request $request,$school_username)
     {
          return $this->StudentAdd->handle($request,$school_username);
     }


     public function student(Request $request,$school_username){
           return $this->StudentList->handle($request,$school_username);
     }

      public function student_update(Request $request,$school_username, $id)
      {
          return $this->StudentUpdate->handle($request,$school_username,$id);
      }
   
 
       public function student_delete(Request $request,$school_username, $id)
       {
           return $this->StudentDelete->handle($request ,$school_username , $id);
       }

       public function student_transfer(Request $request,$school_username)
       {
           return $this->StudentTransfer->handle($request ,$school_username);
       }



       public function student_import(Request $request,$school_username){

            $user_auth=user();
            $session_id=$request->input('session_id');
            $programyear_id=$request->input('programyear_id');
            $level_id=$request->input('level_id');
            $faculty_id=$request->input('faculty_id');
            $department_id=$request->input('department_id');
            $section_id=$request->input('section_id');
       
             Excel::Import(new StudentImport($school_username,$session_id,$programyear_id,$level_id,$faculty_id,$department_id,$section_id,$user_auth),request()->file('file'));
                return response()->json([
                     'message' => 'Student imported successfully',
               ],200);
      }



}
