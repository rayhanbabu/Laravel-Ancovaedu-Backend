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
use App\Services\StudentService\StudentSubject;
use App\Services\StudentService\StudentMark;
use App\Services\StudentService\StudentGroupDelete;
use App\Services\StudentService\MarkDelete;


use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;

class StudentController extends Controller
{

    protected $StudentAdd;
    protected $StudentList;
    protected $StudentUpdate;
    protected $StudentDelete;
    protected $StudentTransfer;
    protected $StudentSubject;
    protected $StudentMark;
    protected $StudentGroupDelete;
    protected $MarkDelete;


    public function __construct(StudentAdd $StudentAdd, StudentList $StudentList, StudentUpdate $StudentUpdate,
     StudentDelete $StudentDelete, StudentTransfer $StudentTransfer ,StudentSubject $StudentSubject
     ,StudentMark $StudentMark ,StudentGroupDelete $StudentGroupDelete,MarkDelete $MarkDelete)
    {
         $this->StudentAdd = $StudentAdd;
         $this->StudentList = $StudentList;
         $this->StudentUpdate = $StudentUpdate;
         $this->StudentDelete = $StudentDelete;
         $this->StudentTransfer = $StudentTransfer;
         $this->StudentSubject = $StudentSubject;
         $this->StudentMark = $StudentMark;
         $this->StudentGroupDelete = $StudentGroupDelete;
         $this->MarkDelete = $MarkDelete;
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

       public function student_subject(Request $request,$school_username,$id)
       {
           return $this->StudentSubject->handle($request ,$school_username,$id);
       }


       public function student_mark(Request $request,$school_username)
       {
           return $this->StudentMark->handle($request ,$school_username);
       }


       public function student_group_delete(Request $request,$school_username)
       {
           return $this->StudentGroupDelete->handle($request ,$school_username);
       }


       public function mark_delete(Request $request,$school_username)
       {
           return $this->MarkDelete->handle($request ,$school_username);
       }


       public function student_import(Request $request,$school_username){

            $user_auth=user();
            $sessionyear_id=$request->input('sessionyear_id');
            $programyear_id=$request->input('programyear_id');
            $level_id=$request->input('level_id');
            $faculty_id=$request->input('faculty_id');
            $department_id=$request->input('department_id');
            $section_id=$request->input('section_id');


            $validator = validator($request->all(), [  
                'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                'programyear_id' => 'required|integer|exists:programyears,id',
                'level_id' => 'required|integer|exists:levels,id',
                'faculty_id' => 'required|integer|exists:faculties,id',
                'department_id' => 'required|integer|exists:departments,id',
                'section_id' => 'required|integer|exists:sections,id',    
                'file' => 'required|mimes:xlsx,xls,csv|max:2048',          
           ]);

             $enroll_group = $request->sessionyear_id."-".$request->programyear_id."-".$request->level_id
                 ."-".$request->faculty_id."-".$request->department_id."-".$request->section_id;

           if($validator->fails()) {
               return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
       
             Excel::Import(new StudentImport($school_username,$sessionyear_id,$programyear_id,$level_id,$faculty_id,$department_id,$section_id,$user_auth,$enroll_group),request()->file('file'));
                return response()->json([
                     'message' => 'Student imported successfully',
               ],200);
      }



}
