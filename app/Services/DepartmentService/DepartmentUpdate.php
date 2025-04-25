<?php
namespace App\Services\DepartmentService;

use App\Models\Department;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class DepartmentUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {

            $user_auth =user();
            $model = Department::findOrFail($id);
            $validator = validator($request->all(), [
                'department_name' => [
                    'required',
                    Rule::unique('departments', 'department_name')
                        ->ignore($id)
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

          $model->department_name = $request->department_name;
          $model->department_status = $request->status;
          $model->faculty_id = $request->faculty_id;
          $model->updated_by = $user_auth->id; 
          
            $model->save();

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
