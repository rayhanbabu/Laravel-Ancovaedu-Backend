<?php
namespace App\Services\GpaCategoryService;

use App\Models\GpaCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class GpaCategoryUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $gpaCategory = GpaCategory::findOrFail($id);

            $validator = validator($request->all(), [
                  'gpa_category_name' => 'required',
                  'status' => 'boolean',
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

            $gpaCategory->school_username = $school_username;
            $gpaCategory->gpa_category_name = $request->gpa_category_name;
            $gpaCategory->status = $request->status;
            $gpaCategory->updated_by = $user_auth->id;
            $gpaCategory->save();

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
