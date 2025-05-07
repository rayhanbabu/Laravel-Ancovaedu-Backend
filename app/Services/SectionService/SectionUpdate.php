<?php
namespace App\Services\SectionService;

use App\Models\Section;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class SectionUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {

            $user_auth =user();
            $model = Section::findOrFail($id);
            $validator = validator($request->all(), [
                'section_name' => [
                    'required',
                    Rule::unique('sections', 'section_name')
                        ->ignore($id)
                        ->where(function ($query) use ($school_username, $request) {
                            return $query->where('school_username', $school_username)
                                         ->where('department_id', $request->department_id);
                        }),
                ],
                'department_id' => [
                    'required',
                    Rule::exists('departments', 'id')->where(function ($query) use ($school_username) {
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

          $model->section_name = $request->section_name;
          $model->section_status = $request->status;
          $model->department_id = $request->department_id;
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
