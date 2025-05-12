<?php
namespace App\Services\PermissionService;


use App\Models\EmployeePermission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class PermissionUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $Permission = EmployeePermission::findOrFail($id);
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
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }
            $Permission->sessionyear_id = $request->sessionyear_id;
            $Permission->programyear_id = $request->programyear_id;
            $Permission->level_id = $request->level_id;
            $Permission->faculty_id = $request->faculty_id;
            $Permission->department_id = $request->department_id;
            $Permission->section_id = $request->section_id;
            $Permission->permission_role = $request->permission_role;
            $Permission->subject_id = $request->subject_id;
            $Permission->employee_user_id = $request->employee_user_id;
            $Permission->updated_by = $user_auth->id; 
            $Permission->save();

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
