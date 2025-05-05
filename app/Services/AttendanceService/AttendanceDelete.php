<?php
namespace App\Services\AttendanceService;

use App\Models\Attendance;
use App\Models\Classdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AttendanceDelete
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
             $classdate = Classdate::findOrFail($id);
               $Attendance = Attendance::where('classdate_id', $id)->where('status',1)->where('school_username', $school_username)->get();
                if ($Attendance->isEmpty()) {
                    $classdate->attendances()->delete(); // Delete all attendance records associated with the classdate
                      $classdate->delete();
                }else {
                    return response()->json([
                        'message' => 'Attendance already taken',
                    ], 400);
                }

           
           
            DB::commit();
            return response()->json([
                'message' => 'Agent deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   
}
