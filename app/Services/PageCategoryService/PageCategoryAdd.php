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
                 'parent_id' => 'nullable|exists:pagecategories,id',
                 'image' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
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
            $user->parent_id = $request->parent_id;
            $user->personal_status = $request->personal_status;
            $user->created_by = $user_auth->id;

             if ($request->hasfile('image')) {
                $user->image = $this->uploadFile($request->file('image'), 'image');
            }


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



      private function uploadFile($file, $prefix)
    {
        $fileName = $prefix . rand() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/admin'), $fileName);
        return $fileName;
    }

}
