<?php

namespace App\Services\SectionService;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class SectionAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'section_name' => [
                    'required',
                    Rule::unique('sections', 'section_name')
                        ->where(function ($query) use ($school_username, $request) {
                            return $query->where('school_username', $school_username)
                                ->where('department_id', $request->department_id);
                        }),
                ],
                'department_id' => [
                    'required',
                    Rule::exists('departments', 'id')
                        ->where(function ($query) use ($school_username) {
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
        
            $model = new Section();
            $model->section_name = $request->section_name;
            $model->department_id = $request->department_id;
            $model->section_status = $request->status;
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
