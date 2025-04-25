<?php

namespace App\Services\ExamService;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;

class ExamAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'exam_name' => 'required|unique:exams,exam_name,NULL,id,school_username,' . $school_username,
            
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

    
            $user_auth =user();
        
            $model = new Exam();
            $model->exam_name = $request->exam_name;
            $model->exam_full_name = $request->exam_full_name;
            $model->exam_status = $request->status;
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
