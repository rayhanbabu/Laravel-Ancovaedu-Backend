<?php

namespace App\Services\PermissionService;

use App\Models\Employeepermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class PermissionAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [     
                
                 'sessionyear_id' => 'nullable|integer|exists:sessionyears,id',
                 'programyear_id' => 'nullable|integer|exists:programyears,id',
                 'level_id' => 'nullable|integer|exists:levels,id',
                 'faculty_id' => 'nullable|integer|exists:faculties,id',
                 'department_id' => 'nullable|integer|exists:departments,id',
                 'section_id' => 'nullable|integer|exists:sections,id',  
                 'subject_id' => 'nullable|integer|exists:subjects,id',    
                 'employee_user_id' => 'required|integer|exists:users,id',  
                 'permission_role' => 'required|exists:permissions,permission',     
                 'exam_id' => 'nullable|exists:exams,id',             
            ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

        
                   $parts = [
                        $request->sessionyear_id,
                        $request->programyear_id,
                        $request->level_id,
                        $request->faculty_id,
                        $request->department_id,
                        $request->section_id,
                    ];

                    
                    if (!empty($request->exam_id)) {
                        $parts[] = $request->exam_id;
                    }

                    if (!empty($request->subject_id)) {
                        $parts[] = $request->subject_id;
                    }

        
            $Permission = new Employeepermission();
            $Permission->school_username = $request->school_username;
            $Permission->sessionyear_id = $request->sessionyear_id;
            $Permission->programyear_id = $request->programyear_id;
            $Permission->level_id = $request->level_id;
            $Permission->faculty_id = $request->faculty_id;
            $Permission->department_id = $request->department_id;
            $Permission->section_id = $request->section_id;
            $Permission->permission_role = $request->permission_role;
            $Permission->subject_id = $request->subject_id;
            $Permission->employee_user_id = $request->employee_user_id;
            $Permission->exam_id = $request->exam_id;
            $Permission->access_group = $access_group;
            $Permission->created_by = $user_auth->id;
            $Permission->save();


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
