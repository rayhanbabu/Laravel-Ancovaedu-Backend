<?php
namespace App\Services\MarkService;

use App\Models\Mark;
use App\Models\Classdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MarkSubmit
{
    public function handle(Request $request, $school_username)
    {
        DB::beginTransaction();
        try {

            $final_submit_status=$request->final_submit_status;

            $user_auth = user();
            $query = Mark::query();
            $query->where('school_username', $school_username)
                  ->where('exam_id', $request->exam_id)
                  ->where('subject_id', $request->subject_id)
                  ->with('enroll')
                  ->whereHas('enroll', function ($q) use ($request) {
                      $q->where('sessionyear_id', $request->sessionyear_id)
                        ->where('programyear_id', $request->programyear_id)
                        ->where('level_id', $request->level_id)
                        ->where('faculty_id', $request->faculty_id)
                        ->where('department_id', $request->department_id);
    
                      if ($request->has('section_id')) {
                          $q->where('section_id', $request->section_id);
                      }
                  });
    
            // Perform the update directly on the query
            $affectedRows = $query->update([
                'final_submit_status' => $final_submit_status,
                'final_submited_by'   => $user_auth->id,
            ]);
    
            DB::commit();
            return response()->json([
                'message' => 'Final  Submitted Status successfully',
                'affected_rows' => $affectedRows
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to Submit agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
   
}
