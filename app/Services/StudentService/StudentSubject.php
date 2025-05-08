<?php
namespace App\Services\StudentService;

use App\Models\School;
use App\Models\Enroll;
use App\Models\Subject;
use App\Models\Mark;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class StudentSubject
{
    public function handle($request,$school_username,$id)
    {
        DB::beginTransaction();

        try {
             $user_auth = user();

              // Validation rules
             $rules = [
                'main_subject1'       => 'nullable|integer|exists:subjects,id',
                'main_subject2'       => 'nullable|integer|exists:subjects,id',
                'main_subject3'       => 'nullable|integer|exists:subjects,id',
                'additional_subject'  => 'nullable|integer|exists:subjects,id',
             ];

              // Validate request
             $validator = validator($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'message' => 'Validation failed',
                     'errors'  => $validator->errors(),
                 ], 422);
              }
                    $enrollment = Enroll::find($id);
                    $enrollment->main_subject1 = $request->main_subject1;
                    $enrollment->main_subject2 = $request->main_subject2;
                    $enrollment->main_subject3 = $request->main_subject3;
                    $enrollment->additional_subject = $request->additional_subject;
                    $enrollment->updated_by = $user_auth->id;
                    $enrollment->save();
            
              DB::commit();

             return response()->json([
                 'message' => "Data Updated Successfully",
             ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to transfer students',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

   
  

}
