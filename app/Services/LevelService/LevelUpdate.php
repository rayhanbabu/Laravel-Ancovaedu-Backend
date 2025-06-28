<?php
namespace App\Services\LevelService;

use App\Models\Level;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class LevelUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {

            $user_auth =user();
            $model = Level::findOrFail($id);

            $validator = validator($request->all(), [
                'level_name' => 'required|unique:levels,level_name,' . $id . ',id,school_username,' . $school_username,
                'level_category' => 'nullable|enum:Secondary,Higher',
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

          $model->level_name = $request->level_name;
          $model->level_status = $request->status;
          $model->level_category = $request->level_category;
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
