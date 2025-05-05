<?php

namespace App\Services\MarkinfoService;

use App\Models\Markinfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class MarkinfoAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [     
                 'start' => 'required|decimal:0,2',
                 'end' => 'required|decimal:0,2',
                 'gpa' => 'required|decimal:0,2',
                 'gparange' => 'required|decimal:0,2',
                 'grade' => 'required|string|max:255',
                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                         
            ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

        

        
            $Markinfo = new Markinfo();
            $Markinfo->school_username = $request->school_username;
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
            $Markinfo->created_by = $user_auth->id;
            $Markinfo->save();


            DB::commit();

            return response()->json([
                  'message' => 'Data added successfully',
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Add ',
                  'error' => $e->getMessage(),
              ], 500);
        }
    }

    
  }
