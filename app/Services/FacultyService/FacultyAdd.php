<?php

namespace App\Services\FacultyService;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class FacultyAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'faculty_name' => [
                    'required',
                    Rule::unique('faculties')->where(function ($query) use ($school_username, $request) {
                        return $query->where('school_username', $school_username)
                                     ->where('level_id', $request->level_id);
                    }),
                ],
                'level_id' => [
                    'required',
                    Rule::exists('levels', 'id')->where(function ($query) use ($school_username) {
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
        
            $model = new Faculty();
            $model->faculty_name = $request->faculty_name;
            $model->level_id = $request->level_id;
            $model->faculty_status = $request->status;
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
