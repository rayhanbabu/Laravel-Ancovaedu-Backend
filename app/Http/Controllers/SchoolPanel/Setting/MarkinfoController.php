<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Markinfo;

use App\Services\MarkinfoService\MarkinfoAdd;
use App\Services\MarkinfoService\MarkinfoList;
use App\Services\MarkinfoService\MarkinfoUpdate;
use App\Services\MarkinfoService\MarkinfoDelete;
use App\Services\MarkinfoService\MarkinfoTransfer;


use App\Exports\MarkinfoExport;
use Maatwebsite\Excel\Facades\Excel;




class MarkinfoController extends Controller
{

    protected $MarkinfoAdd;
    protected $MarkinfoList;
    protected $MarkinfoUpdate;
    protected $MarkinfoDelete;
    protected $MarkinfoTransfer;
   

    public function __construct(MarkinfoAdd $MarkinfoAdd, MarkinfoList $MarkinfoList, MarkinfoUpdate $MarkinfoUpdate,
     MarkinfoDelete $MarkinfoDelete, MarkinfoTransfer $MarkinfoTransfer)
    {
         $this->MarkinfoAdd = $MarkinfoAdd;
         $this->MarkinfoList = $MarkinfoList;
         $this->MarkinfoUpdate = $MarkinfoUpdate;
         $this->MarkinfoDelete = $MarkinfoDelete;
         $this->MarkinfoTransfer = $MarkinfoTransfer;
    }

  
     public function markinfo_add(Request $request,$school_username)
       {
           return $this->MarkinfoAdd->handle($request,$school_username);
       }


     public function markinfo(Request $request,$school_username){
           return $this->MarkinfoList->handle($request,$school_username);
     }

      public function markinfo_update(Request $request,$school_username, $id)
      {
          return $this->MarkinfoUpdate->handle($request,$school_username,$id);
      }
   
 
       public function markinfo_delete(Request $request,$school_username, $id)
       {
           return $this->MarkinfoDelete->handle($request ,$school_username , $id);
       }

       public function markinfo_transfer(Request $request, $school_username){
             return $this->MarkinfoTransfer->handle($request, $school_username);
       }


          public function markinfo_export(Request $request ,$school_username)
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

               $markinfo_group = $request->sessionyear_id."-".$request->programyear_id."-".$request->level_id
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

        return Excel::download(new MarkinfoExport($filters), 'markinfo-' . $markinfo_group . '.xlsx');
     }



        public function markinfo_import(Request $request, $school_username)
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

    $markinfo_group = "$sessionyear_id-$programyear_id-$level_id-$faculty_id-$department_id-$section_id";

    // Check if markinfo already exists
    $exists = Markinfo::where([
        ['school_username', $school_username],
        ['sessionyear_id', $sessionyear_id],
        ['programyear_id', $programyear_id],
        ['level_id', $level_id],
        ['faculty_id', $faculty_id],
        ['department_id', $department_id],
    ])->exists();

    if ($exists) {
        return response()->json([
            'message' => 'Markinfo already exists in the target session',
        ], 400);
    }

    $file = $request->file('file');
    
    // Map extensions to Excel reader types
    $readerTypeMap = [
        'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
        'xls'  => \Maatwebsite\Excel\Excel::XLS,
        'csv'  => \Maatwebsite\Excel\Excel::CSV,
    ];
    
    $extension = strtolower($file->getClientOriginalExtension());
    $readerType = $readerTypeMap[$extension] ?? null;

    // Handle invalid extensions (shouldn't happen due to validation, but safe-guard)
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

        foreach ($data->skip(1) as $row) {
            $markinfo = new Markinfo();
            $markinfo->school_username = $school_username;
            $markinfo->sessionyear_id = $sessionyear_id;
            $markinfo->programyear_id = $programyear_id;
            $markinfo->level_id = $level_id;
            $markinfo->faculty_id = $faculty_id;
            $markinfo->department_id = $department_id;
            $markinfo->section_id = $section_id;
            $markinfo->markinfo_group = $markinfo_group;

            $markinfo->start = $row[0] ?? null;
            $markinfo->end = $row[1] ?? null;
            $markinfo->gpa = $row[2] ?? null;
            $markinfo->grade = $row[3] ?? null;
            $markinfo->gparange = $row[4] ?? null;

            $markinfo->created_by = $user_auth->id;
            $markinfo->save();
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Markinfo imported successfully!'
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to import Markinfo',
            'error' => $e->getMessage(),
        ], 500);
    }
}



     

}
