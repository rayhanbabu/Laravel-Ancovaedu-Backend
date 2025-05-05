<?php
namespace App\Services\MarkinfoService;


use App\Models\Markinfo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class MarkinfoUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $Markinfo = Markinfo::findOrFail($id);
            $validator = validator($request->all(), [
                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                 'start' => 'required|decimal:0,2',
                 'end' => 'required|decimal:0,2',
                 'gpa' => 'required|decimal:0,2',
                 'gparange' => 'required|decimal:0,2',
                 'grade' => 'required|string|max:255',
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

         
          $Markinfo->sessionyear_id = $request->sessionyear_id;
          $Markinfo->programyear_id = $request->programyear_id;
          $Markinfo->level_id = $request->level_id;
          $Markinfo->faculty_id = $request->faculty_id;
          $Markinfo->department_id = $request->department_id;
          $Markinfo->section_id = $request->section_id;
          $Markinfo->start = $request->start;
          $Markinfo->end = $request->end;
          $Markinfo->gpa = $request->gpa;
          $Markinfo->gparange = $request->gparange;
          $Markinfo->grade = $request->grade;
          $Markinfo->updated_by = $user_auth->id; 
          $Markinfo->save();

            DB::commit();

            return response()->json([
                'message' => 'Data updated successfully',
            ], 200);

         } catch (\Exception $e) {
             DB::rollback();
              return response()->json([
                  'status' => 'error',
                  'message' => 'Failed to update school',
                  'error' => $e->getMessage(),
              ], 500);
         }
    }


}
