<?php

namespace App\Services\PageCategoryService;

use App\Models\Pagecategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class PageCategoryAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {

            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [
                 'page_category_name' => 'required|unique:pagecategories,page_category_name,NULL,id,school_username,' . $username,
                 'status' => 'nullable|boolean',
              ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

            $user = new Pagecategory();
            $user->school_username = $username;
            $user->page_category_name = $request->page_category_name;
            $user->personal_status = $request->personal_status;
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
