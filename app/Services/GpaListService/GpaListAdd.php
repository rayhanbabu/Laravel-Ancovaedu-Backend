<?php

namespace App\Services\GpaListService;

use App\Models\Gpacategory;
use App\Models\Gpalist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class GpaListAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {

            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [
                 'session_year' => 'required',
                 'gpa_category_id' => [
                        'required',
                        Rule::exists('gpacategories', 'id')->where(function ($query) use ($username) {
                            $query->where('school_username', $username);
                        }),
                    ],
                    'status' => 'boolean',
                    'total_student' => 'required|integer',
                    'total_pass' => 'required|integer',
                    'gpa5' => 'nullable|integer',
                    'gpa4' => 'nullable|integer',
                    'gpa3' => 'nullable|integer',
                    'gpa35' => 'nullable|integer',
                    'gpa2' => 'nullable|integer',
                    'gpa1' => 'nullable|integer',
                    'gpa0' => 'nullable|integer',
              ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

            $user = new Gpalist();
            $user->school_username = $username;
            $user->gpa_category_id = $request->gpa_category_id;
            $user->session_year = $request->session_year;
            $user->total_student = $request->total_student;
            $user->total_pass = $request->total_pass;
            $user->total_fail = ($request->total_student - $request->total_pass);
            $user->pass_rate = ($request->total_student > 0) ? ($request->total_pass / $request->total_student * 100) : 0;
            $user->gpa5 = $request->gpa5;
            $user->gpa4 = $request->gpa4;
            $user->gpa3 = $request->gpa3;
            $user->gpa35 = $request->gpa35;
            $user->gpa2 = $request->gpa2;
            $user->gpa1 = $request->gpa1;
            $user->gpa0 = $request->gpa0;
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
