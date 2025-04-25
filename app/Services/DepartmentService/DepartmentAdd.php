<?php

namespace App\Services\DepartmentService;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class DepartmentAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'department_name' => [
                    'required',
                    Rule::unique('departments', 'department_name')
                        ->where(function ($query) use ($school_username, $request) {
                            return $query->where('school_username', $school_username)
                                         ->where('faculty_id', $request->faculty_id);
                        }),
                ],
                'faculty_id' => [
                    'required',
                    Rule::exists('faculties', 'id')->where(function ($query) use ($school_username) {
                        return $query->where('school_username', $school_username);
                    }),
                ],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

    
            $user_auth =user();
        
            $model = new Department();
            $model->department_name = $request->department_name;
            $model->faculty_id = $request->faculty_id;
            $model->Department_status = $request->status;
            $model->created_by = $user_auth->id; 
            $model->school_username = $school_username;
            $model->save();

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
