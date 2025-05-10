<?php
namespace App\Services\StudentService;

use App\Models\School;
use App\Models\Enroll;
use App\Models\Subject;
use App\Models\Mark;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class MarkDelete
{
    public function handle($request, $school_username)
    {
        DB::beginTransaction();
    
        try {
            $user_auth = user();
    
            // Validation rules
            $rules = [
                'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                'programyear_id' => 'required|integer|exists:programyears,id',
                'level_id'       => 'required|integer|exists:levels,id',
                'faculty_id'     => 'required|integer|exists:faculties,id',
                'department_id'  => 'required|integer|exists:departments,id',
                'section_id'     => 'required|integer|exists:sections,id',
                'exam_id'        => 'required|integer|exists:exams,id',
            ];
    
            // Validate request
            $validator = validator($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }
    
            // Build the query
            $query = Mark::where('exam_id', $request->exam_id)
                ->where('school_username', $school_username)
                ->whereHas('enroll', function ($q) use ($request) {
                    $q->where('sessionyear_id', $request->sessionyear_id)
                      ->where('programyear_id', $request->programyear_id)
                      ->where('level_id', $request->level_id)
                      ->where('faculty_id', $request->faculty_id)
                      ->where('department_id', $request->department_id)
                      ->where('section_id', $request->section_id);
                });
    
            // Sum the total of matched marks
            $totalSum = $query->sum('total');
    
            if ($totalSum <= 0) {
                // Count how many records to be deleted
                $deleteCount = $query->count();
    
                // Delete records
                $query->delete();
    
                DB::commit();
                return response()->json([
                    'message'        => 'Marks deleted successfully because total sum was less than 0.',
                    'total_deleted'  => $deleteCount,
                    'sum_of_total'   => $totalSum,
                ], 200);
            } else {
                DB::rollBack();
                return response()->json([
                    'message'      => 'Marks not deleted because total sum is not less than 0.',
                    'sum_of_total' => $totalSum,
                ], 404);
            }
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete marks',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    
    
    /**
     * Create a Mark record for a student and subject.
     */
   
    
  

}
