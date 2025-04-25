<?php
namespace App\Services\ReligionService;

use App\Models\Religion;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class ReligionUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {

            $user_auth =user();
            $model = Religion::findOrFail($id);

            $validator = validator($request->all(), [
                'religion_name' => 'required|unique:religions,religion_name,' . $id . 'NULL,id,school_username,' . $school_username,
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

          $model->religion_name = $request->religion_name;
          $model->religion_status = $request->status;
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
