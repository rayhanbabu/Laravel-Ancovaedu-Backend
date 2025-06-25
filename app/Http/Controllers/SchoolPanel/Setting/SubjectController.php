<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Subject;

use App\Services\SubjectService\SubjectAdd;
use App\Services\SubjectService\SubjectList;
use App\Services\SubjectService\SubjectUpdate;
use App\Services\SubjectService\SubjectDelete;
use App\Services\SubjectService\SubjectTransfer;

use App\Exports\SubjectExport;
use Maatwebsite\Excel\Facades\Excel;

class SubjectController extends Controller
{

    protected $SubjectAdd;
    protected $SubjectList;
    protected $SubjectUpdate;
    protected $SubjectDelete;
    protected $SubjectTransfer;

    public function __construct(SubjectAdd $SubjectAdd, SubjectList $SubjectList, SubjectUpdate $SubjectUpdate, SubjectDelete $SubjectDelete, SubjectTransfer $SubjectTransfer)
    {
         $this->SubjectAdd = $SubjectAdd;
         $this->SubjectList = $SubjectList;
         $this->SubjectUpdate = $SubjectUpdate;
         $this->SubjectDelete = $SubjectDelete;
         $this->SubjectTransfer = $SubjectTransfer;
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

         public function subject_transfer(Request $request, $school_username)
       {
           return $this->SubjectTransfer->handle($request ,$school_username);
       }

       public function subject_export(Request $request ,$school_username)
        {
          $validator = validator($request->all(), [
                'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                'programyear_id' => 'required|integer|exists:programyears,id',
                'level_id' => 'required|integer|exists:levels,id',
                'faculty_id' => 'required|integer|exists:faculties,id',
                'department_id' => 'required|integer|exists:departments,id',
                'section_id' => 'required|integer|exists:sections,id',  
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

               $subject_group = $request->sessionyear_id."-".$request->programyear_id."-".$request->level_id
                 ."-".$request->faculty_id."-".$request->department_id."-".$request->section_id;


       $filters = [
           ['sessionyear_id', '=', $request->sessionyear_id],
           ['programyear_id', '=', $request->programyear_id],
           ['level_id', '=', $request->level_id],
           ['faculty_id', '=', $request->faculty_id],
           ['department_id', '=', $request->department_id],
           ['section_id', '=', $request->section_id],
           ['school_username', '=', $school_username],
        ];

        return Excel::download(new SubjectExport($filters), 'subjects-' . $subject_group . '.xlsx');
     }



    public function subject_import(Request $request, $school_username)
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

    $subject_group = "$sessionyear_id-$programyear_id-$level_id-$faculty_id-$department_id-$section_id";


               $exists = Subject::where([
                        ['school_username', $school_username],
                        ['sessionyear_id', $request->sessionyear_id],
                        ['programyear_id', $request->programyear_id],
                        ['level_id', $request->level_id],
                        ['faculty_id', $request->faculty_id],
                        ['department_id', $request->department_id],
                    ])->exists();

            if ($exists) {
              return response()->json([
                        'message' => 'Subject already exists in the target session',
                 ], 400);
            }

    $path = $request->file('file')->getRealPath();
    $data = Excel::toCollection(null, $path)->first();

    foreach ($data->skip(1) as $row) {
        DB::transaction(function () use ($row, $school_username, $sessionyear_id, $programyear_id, $level_id, $faculty_id, $department_id, $section_id, $subject_group, $user_auth) {
            $subject = new Subject();
            $subject->school_username = $school_username;
            $subject->sessionyear_id = $sessionyear_id;
            $subject->programyear_id = $programyear_id;
            $subject->level_id = $level_id;
            $subject->faculty_id = $faculty_id;
            $subject->department_id = $department_id;
            $subject->section_id = $section_id;
            $subject->subject_group = $subject_group;
            $subject->subject_name = $row[0] ?? null;
            $subject->subject_code = $row[1] ?? null;
            $subject->serial = $row[2] ?? null;
            $subject->gpa_calculation = $row[3] ?? null;
            $subject->input_lavel1 = $row[4] ?? null;
            $subject->input_lavel2 = $row[5] ?? null;
            $subject->input_lavel3 = $row[6] ?? null;
            $subject->input_number1 = $row[7] ?? null;
            $subject->input_number2 = $row[8] ?? null;
            $subject->input_number3 = $row[9] ?? null;
            $subject->total_number = $row[10] ?? null;
            $subject->pass_number1 = $row[11] ?? null;
            $subject->pass_number2 = $row[12] ?? null;
            $subject->pass_number3 = $row[13] ?? null;
            $subject->subject_category = $row[14] ?? null;
            $subject->religion_id = $row[15] ?? null;
            $subject->subject_type = $row[16] ?? null;
            $subject->created_by = $user_auth->id;
            $subject->save();
        });
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Subjects imported successfully!'
    ], 200);
}








}
