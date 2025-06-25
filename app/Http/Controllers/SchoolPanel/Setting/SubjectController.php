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

use App\Exports\SubjectExport;
use Maatwebsite\Excel\Facades\Excel;

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



}
