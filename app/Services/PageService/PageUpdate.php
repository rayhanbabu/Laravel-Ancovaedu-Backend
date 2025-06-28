<?php
namespace App\Services\PageService;

use App\Models\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class PageUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $Page = Page::findOrFail($id);

          $validator = validator($request->all(), [
                 'title' => 'required|string|max:255',
                 'date'=> 'required|date',
                 'content' => 'nullable',
                 'link' => 'nullable|max:255',
                 'image' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
                 'page_category_id' => 'required|exists:pagecategories,id',
                 'status' => 'nullable|boolean',

                  'serial' => 'nullable|integer|max:255',

                 'name' => 'nullable|string|max:255',
                 'phone' => 'nullable|string|max:15',
                 'email' => 'nullable|max:255',
                 'designation' => 'nullable|string|max:255',
              ]);

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

            $Page->school_username = $school_username;
            $Page->page_category_id = $request->page_category_id;
            $Page->title = $request->title;
            $Page->date = $request->date;
            $Page->content = $request->content;
            $Page->link = $request->link;
            $Page->name = $request->name;
            $Page->phone = $request->phone;
            $Page->serial = $request->serial;
            $Page->email = $request->email;
            $Page->designation = $request->designation;
            $Page->status = $request->status;
            $Page->updated_by = $user_auth->id;

         
             if ($request->hasFile('image')) {
                 $this->handleImageUpload($request, $Page);
              }

            $Page->save();

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



    private function handleImageUpload($request, $Page)
    {
        $path = public_path('uploads/admin') . '/' . $Page->image;
        if ($Page->image && File::exists($path)) {
            File::delete($path);
        }
        $image = $request->file('image');
        $fileName = 'image' . rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/admin'), $fileName);
        $Page->image = $fileName;
    }

}
