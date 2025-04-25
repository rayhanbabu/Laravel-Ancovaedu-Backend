<?php
namespace App\Services\ExamService;

use App\Models\Exam;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class ExamUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {

            $user_auth =user();
            $model = Exam::findOrFail($id);

            $validator = validator($request->all(), [
                'exam_name' => 'required|unique:exams,exam_name,' . $id . 'NULL,id,school_username,' . $school_username,
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

          $model->exam_name = $request->exam_name;
          $model->exam_full_name = $request->exam_full_name;
          $model->exam_status = $request->status;
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
