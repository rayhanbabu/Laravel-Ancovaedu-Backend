<?php

namespace App\Http\Controllers\SchoolPanel\SchoolAccount;

use App\Models\Category;
use App\Models\Balance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
  {
     public function category_add(Request $request,$school_username)  {


         DB::beginTransaction();

         try {
            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [     
                'category_name' => [
                    'required',
                    Rule::unique('categories', 'category_name')
                        ->where(function ($query) use ($school_username, $request) {
                            return $query->where('school_username',$school_username)
                                ->where('category_name', $request->category_name);
                        }),
                ],
                 'category_type' => 'required',  
              ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

            $model = new Category();
            $model->school_username = $request->school_username;
            $model->category_name =$request->category_name;
            $model->category_type =$request->category_type;
            $model->status = 1;
            $model->created_by = $user_auth->id;
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

       

     public function category(Request $request,$school_username){
        $query = Category::query();  
        $query->where('school_username', $school_username);

       // Search
      if ($request->has('search')) {
         $search = $request->search;
          $query->where(function ($q) use ($search) {
            $q->where('category_type', 'like', "%$search%")
                ->orWhere('category_name', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
         });
       }

        // Filter by status
        if ($request->has('status')) {
                $query->where('status', $request->status);
        }

          // View By Id
         if ($request->has('category_type')) {
             $query->where('category_type', $request->category_type);
         }

         // View By Id
         if ($request->has('viewById')) {
             $query->where('id', $request->viewById);
         }

        // Sorting
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        $perPage = ($perPage > 100) ? 100 : $perPage; // Max 100 per page

        // Apply pagination
        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
                'data' =>$result->items(),
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem()
           
        ]);
     }




     function category_update(Request $request, $school_username, $id)
            {
                $user_auth = user();
                $model = Category::findOrFail($id);

                // Validation
                $validator = validator($request->all(), [
                    'category_name' => [
                        'required',
                        Rule::unique('categories', 'category_name')
                            ->ignore($id)
                            ->where(function ($query) use ($school_username) {
                                return $query->where('school_username', $school_username);
                            }),
                    ],
                    'category_type' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Validation failed',
                        'errors' => $validator->errors(),
                    ], 422);
                }

               
                if (Balance::where('category_id', $id)->exists()) {
                    return response()->json([
                        'message' => 'Cannot update — this category is linked to existing payment information.',
                    ], 400);
                }

              
                DB::beginTransaction();
                try {
                    $model->category_name = $request->category_name;
                    $model->category_type = $request->category_type;
                    $model->status = $request->status;
                    $model->updated_by = $user_auth->id;

                    $model->save();

                    DB::commit();

                    return response()->json([
                        'message' => 'Data updated successfully',
                    ], 200);

                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to update category',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }
   
 
       public function category_delete(Request $request,$school_username, $id)
       {
            DB::beginTransaction();
         try {
            $model = Category::findOrFail($id); 
            // Delete agent and user
            $model->delete();

            DB::commit();
            return response()->json([
                'message' => 'Data deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete agent',
                'error' => $e->getMessage(),
            ], 500);
        }
       }

     

}
