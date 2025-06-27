<?php
namespace App\Services\FeetypeService;

use App\Models\Feetype;
use App\Models\Invoice;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class FeetypeUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth =user();
            $data=Invoice::where('feetype_id', $id)
                ->where('school_username', $school_username)
                ->count();

           if($data > 0) {
                 return response()->json([
                      'message' => 'This feetype is used in invoice, so you can not update it',
                 ], 400);
             }

            $model = Feetype::findOrFail($id);

            $validator = validator($request->all(), [
                'feetype_name' => 'required|unique:feetypes,feetype_name,' . $id . 'NULL,id,school_username,' . $school_username,
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

          $model->feetype_name = $request->feetype_name;
          $model->feetype_status = $request->status;
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
