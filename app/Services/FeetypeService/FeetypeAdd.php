<?php

namespace App\Services\FeetypeService;

use App\Models\Feetype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;

class FeetypeAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'feetype_name' => 'required|unique:feetypes,feetype_name,NULL,id,school_username,' . $school_username,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }


            $user_auth = user();

            $model = new Feetype();
            $model->feetype_name = $request->feetype_name;
            $model->feetype_status = $request->status;
            $model->created_by = $user_auth->id;
            $model->school_username = $school_username;
            $model->save();

            DB::commit();

            return response()->json([
                  'message' => 'Data added successfully',
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Add ',
                  'error' => $e->getMessage(),
              ], 500);
        }
    }

 
}
