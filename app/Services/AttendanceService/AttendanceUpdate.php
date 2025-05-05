<?php
namespace App\Services\AttendanceService;


use App\Models\Attendance;
use App\Models\Classdate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class AttendanceUpdate
{
    public function handle($request, $school_username)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
          
            $validator = validator($request->all(), [
               
                 'id' => 'required|array',
                 'id.*' => 'required|integer|exists:attendances,id',
                 'status' => 'required|array',
                 'status.*' => 'required|integer|in:0,1',
               
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

          $ids = array_values(array_filter(array_unique($request->id)));
          $statuss =$request->status;

          for ($i = 0; $i < count($ids); $i++) {
                $Attendance = Attendance::find($ids[$i]);
                if ($Attendance) {
                    $Attendance->status = $statuss[$i];
                    $Attendance->save();
                }
          }

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
