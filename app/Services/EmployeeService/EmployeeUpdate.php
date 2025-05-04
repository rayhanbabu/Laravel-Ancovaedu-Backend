<?php
namespace App\Services\EmployeeService;

use App\Models\School;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class EmployeeUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $Employee = Employee::findOrFail($id);

            $validator = validator($request->all(), [
                 'english_name' => 'required',
                 'bangla_name' => 'required',
                 'email' => 'required|unique:users,email,' . $Employee->user_id,
                 'phone' => 'required|unique:users,phone,' . $Employee->user_id,
                 'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:600',
                 'religion_id' => 'required|integer|exists:religions,id',
                 'religion_id' => 'required|integer|exists:religions,id',                
                 'gender' => 'required',
                 'level_id' => 'exists:levels,id',
                 'faculty_id' => 'exists:faculties,id',
                 'department_id' => 'exists:departments,id',
                 'designation_id' => 'required|integer|exists:designations,id',
               
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

             $Employee->user->name = $request->english_name;
             $Employee->user->email = $request->email;
             $Employee->user->phone = $request->phone;

            

             $Employee->bangla_name = $request->bangla_name;
             $Employee->english_name = $request->english_name;
             $Employee->gender= $request->gender;
             $Employee->religion_id = $request->religion_id;
             $Employee->relationship = $request->relationship;
             $Employee->blood_group = $request->blood_group;
             $Employee->joining_date = $request->joining_date;
             $Employee->index_number = $request->index_number;
             $Employee->tin_number = $request->tin_number;
             $Employee->dob = $request->dob;
             $Employee->father_name = $request->father_name;
             $Employee->mother_name = $request->mother_name;
             $Employee->spouse_name = $request->spouse_name;
             $Employee->present_address = $request->present_address;
             $Employee->permanent_address = $request->permanent_address;
             $Employee->level_id = $request->level_id;
             $Employee->faculty_id = $request->faculty_id;
             $Employee->department_id = $request->department_id;
             $Employee->designation_id = $request->designation_id;
 
             $Employee->account_name = $request->account_name;
             $Employee->account_number = $request->account_number;
             $Employee->bank_name = $request->bank_name;
             $Employee->branch_name = $request->branch_name;
             $Employee->swift_code = $request->swift_code;
             $Employee->routing_number = $request->routing_number;

             $Employee->updated_by = $user_auth->id; 
       
          
             if ($request->hasFile('profile_picture')) {
                 $this->handleProfilePictureUpload($request, $school);
              }

           
             $Employee->user->save();
             $Employee->save();

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

   
    private function handleProfilePictureUpload($request, $school)
    {
        $path = public_path('uploads/admin') . '/' . $school->user->profile_picture;
        if ($school->user->profile_picture && File::exists($path)) {
            File::delete($path);
        }
        $image = $request->file('profile_picture');
        $fileName = 'profile_picture' . rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/admin'), $fileName);
        $school->user->profile_picture = $fileName;
    }

}
