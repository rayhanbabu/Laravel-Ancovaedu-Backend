<?php
namespace App\Services\PageCategoryService;

use App\Models\Pagecategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class PageCategoryUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $PageCategory = Pagecategory::findOrFail($id);

            $validator = validator($request->all(), [
                  'page_category_name' => 'required|unique:pagecategories,page_category_name,' . $id . ',id,school_username,' . $school_username,
                  'status' => 'boolean',
                  'parent_id' => 'nullable|exists:pagecategories,id',
                  'image' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

            $PageCategory->school_username = $school_username;
            $PageCategory->page_category_name = $request->page_category_name;
            $PageCategory->personal_status = $request->personal_status;
            $PageCategory->parent_id = $request->parent_id;
            $PageCategory->status = $request->status;
            $PageCategory->updated_by = $user_auth->id;

               if ($request->hasFile('image')) {
                 $this->handleImageUpload($request, $PageCategory);
              }

            $PageCategory->save();

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

    private function handleImageUpload($request, $PageCategory)
    {
        $path = public_path('uploads/admin') . '/' . $PageCategory->image;
        if ($PageCategory->image && File::exists($path)) {
            File::delete($path);
        }
        $image = $request->file('image');
        $fileName = 'image' . rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/admin'), $fileName);
        $PageCategory->image = $fileName;
    }
   


   
}
