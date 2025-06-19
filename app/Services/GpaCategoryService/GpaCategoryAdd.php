<?php

namespace App\Services\GpaCategoryService;

use App\Models\Gpacategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class GpaCategoryAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {

            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [
                 'gpa_category_name' => 'required',
                 'status' => 'boolean',
              ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

            $user = new Gpacategory();
            $user->school_username = $username;
            $user->gpa_category_name = $request->gpa_category_name;
            $user->created_by = $user_auth->id;

            $user->save();

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
