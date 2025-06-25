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
use Maatwebsite\Excel\HeadingRowImport;
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





   public function student_import(Request $request, $school_username)
{
    $user_auth = user();
    $sessionyear_id = $request->input('sessionyear_id');
    $programyear_id = $request->input('programyear_id');
    $level_id = $request->input('level_id');
    $faculty_id = $request->input('faculty_id');
    $department_id = $request->input('department_id');
    $section_id = $request->input('section_id');

    $validator = validator($request->all(), [
        'sessionyear_id' => 'required|integer|exists:sessionyears,id',
        'programyear_id' => 'required|integer|exists:programyears,id',
        'level_id' => 'required|integer|exists:levels,id',
        'faculty_id' => 'required|integer|exists:faculties,id',
        'department_id' => 'required|integer|exists:departments,id',
        'section_id' => 'required|integer|exists:sections,id',
        'file' => 'required|mimes:xlsx,xls,csv|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    $enroll_group = "$sessionyear_id-$programyear_id-$level_id-$faculty_id-$department_id-$section_id";
    $file = $request->file('file');
    
    // Create extension to reader type mapping
    $readerTypeMap = [
        'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
        'xls'  => \Maatwebsite\Excel\Excel::XLS,
        'csv'  => \Maatwebsite\Excel\Excel::CSV,
    ];
    
    $extension = strtolower($file->getClientOriginalExtension());
    $readerType = $readerTypeMap[$extension] ?? null;

    // Handle invalid extensions
    if (!$readerType) {
        return response()->json([
            'message' => 'Unsupported file type. Valid types: xlsx, xls, csv',
        ], 422);
    }

    DB::beginTransaction();

    try {
        // Read file with explicit reader type
        $data = Excel::toCollection(
            null, 
            $file->getRealPath(), 
            null, 
            $readerType
        )->first();

        // Mobile numbers from Excel (assuming column index 2)
        $mobileNumbers = $data->skip(1)->pluck(2)->filter()->unique();

        // Find duplicates in database
        $duplicates = \App\Models\User::whereIn('phone', $mobileNumbers)->pluck('phone');

        if ($duplicates->isNotEmpty()) {
            DB::rollBack();
            return response()->json([
                'status' => 'duplicate_found',
                'duplicates' => $duplicates
            ], 400);
        }

        // Import all students
        foreach ($data->skip(1) as $row) {
            $user = new \App\Models\User();
            $user->name = $row[0] ?? null;
            $user->phone = $row[2] ?? null;
            $user->email = $row[3] ?? null;
            $user->password = bcrypt("Rayhan12");
            $user->status = 1;
            $user->first_phone = substr($row[2] ?? '', 0, 3);
            $user->last_phone = substr($row[2] ?? '', 3);
            $user->username = $school_username . ($row[2] ?? '');
            $user->save();

            \App\Models\User_role::create([
                'user_id' => $user->id,
                'role_type' => 'Student',
                'created_by' => $user_auth->id,
            ]);

            $student = new \App\Models\Student();
            $student->user_id = $user->id;
            $student->school_username = $school_username;
            $student->english_name = $row[0] ?? null;
            $student->bangla_name = $row[1] ?? null;
            $student->gender = $row[4] ?? null;
            $student->religion_id = $row[5] ?? null;
            $student->created_by = $user_auth->id;
            $student->save();

            $enroll = new \App\Models\Enroll();
            $enroll->student_id = $student->id;
            $enroll->user_id = $user->id;
            $enroll->roll = $row[6] ?? null;
            $enroll->school_username = $school_username;
            $enroll->sessionyear_id = $sessionyear_id;
            $enroll->programyear_id = $programyear_id;
            $enroll->level_id = $level_id;
            $enroll->faculty_id = $faculty_id;
            $enroll->department_id = $department_id;
            $enroll->section_id = $section_id;
            $enroll->enroll_group = $enroll_group;
            $enroll->created_by = $user_auth->id;
            $enroll->created_type = "Enroll";
            $enroll->save();
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Students imported successfully!'
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Failed to import Students',
            'error' => $e->getMessage(),
        ], 500);
    }
}



}
