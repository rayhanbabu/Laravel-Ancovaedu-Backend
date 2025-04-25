<?php
namespace App\Services\FacultyService;

use App\Models\Faculty;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class FacultyUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {

            $user_auth =user();
            $model = Faculty::findOrFail($id);

            $validator = validator($request->all(), [
                'faculty_name' => [
                    'required',
                    Rule::unique('faculties', 'faculty_name')
                        ->ignore($id) // <-- this skips the current record for uniqueness check
                        ->where(function ($query) use ($school_username, $request) {
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

          $model->faculty_name = $request->faculty_name;
          $model->faculty_status = $request->status;
          $model->level_id = $request->level_id;
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
