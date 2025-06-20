<?php
namespace App\Services\GpaListService;

use App\Models\GpaList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class GpaListUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $gpaList = GpaList::findOrFail($id);

            $validator = validator($request->all(), [
                  'gpa_category_id' => 'required|exists:gpacategories,id,school_username,' . $school_username,
                  'status' => 'boolean',
                    'session_year' => 'required|string|max:255',
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


         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

            $gpaList->school_username = $school_username;
            $gpaList->gpa_category_id = $request->gpa_category_id;
            $gpaList->session_year = $request->session_year;
            $gpaList->total_student = $request->total_student;
            $gpaList->total_pass = $request->total_pass;
            $gpaList->total_fail = $request->total_student - $request->total_pass;
            $gpaList->pass_rate = ($request->total_student > 0) ? ($request->total_pass / $request->total_student * 100) : 0;
            $gpaList->gpa5 = $request->gpa5;
            $gpaList->gpa4 = $request->gpa4;
            $gpaList->gpa3 = $request->gpa3;
            $gpaList->gpa35 = $request->gpa35;
            $gpaList->gpa2 = $request->gpa2;
            $gpaList->gpa1 = $request->gpa1;
            $gpaList->gpa0 = $request->gpa0;
            $gpaList->status = $request->status;
            $gpaList->updated_by = $user_auth->id;
            $gpaList->save();

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
