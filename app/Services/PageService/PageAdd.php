<?php

namespace App\Services\PageService;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class PageAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {

            $user_auth = user();
            $username = $request->school_username;

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

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

            $user = new Page();
            $user->school_username = $username;
            $user->page_category_id = $request->page_category_id;
            $user->title = $request->title;
            $user->date = $request->date;
            $user->content = $request->content;
            $user->link = $request->link;
            $user->name = $request->name;
            $user->serial = $request->serial;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->designation = $request->designation;
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
