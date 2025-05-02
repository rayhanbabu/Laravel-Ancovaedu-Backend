<?php
namespace App\Services\FeeService;


use App\Models\Fee;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class FeeUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $fee = Fee::findOrFail($id);
            $validator = validator($request->all(), [
                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                 'section_id' => 'required|integer|exists:sections,id',   
                 'desc' => 'required', 
                 'amount' => 'required|integer',   
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

            $fee->school_username = $request->school_username;
            $fee->sessionyear_id = $request->sessionyear_id;
            $fee->programyear_id = $request->programyear_id;
            $fee->level_id = $request->level_id;
            $fee->faculty_id = $request->faculty_id;
            $fee->department_id = $request->department_id;
            $fee->section_id = $request->section_id;
            $fee->desc = $request->desc;
            $fee->amount = $request->amount;
            $fee->fee_type = $request->fee_type;
            $fee->updated_by = $user_auth->id; 
            $fee->save();

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
