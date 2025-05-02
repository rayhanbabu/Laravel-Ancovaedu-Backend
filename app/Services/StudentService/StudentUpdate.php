<?php
namespace App\Services\StudentService;

use App\Models\School;
use App\Models\Enroll;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class StudentUpdate
{
    public function handle($request, $school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $enroll = Enroll::findOrFail($id);

            $validator = validator($request->all(), [
                 'english_name' => 'required',
                 'bangla_name' => 'required',
                 'email' => 'required|unique:users,email,' . $enroll->user_id,
                 'phone' => 'required|unique:users,phone,' . $enroll->user_id,
                 'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:600',
                 'religion_id' => 'required|integer|exists:religions,id',
                 'gender' => 'required',
                 'roll' => 'required|integer',
                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                 'section_id' => 'required|integer|exists:sections,id',         
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

             $enroll->user->name = $request->english_name;
             $enroll->user->email = $request->email;
             $enroll->user->phone = $request->phone;

            

             $enroll->student->english_name = $request->bangla_name;
             $enroll->student->english_name = $request->english_name;
             $enroll->student->gender= $request->gender;
             $enroll->student->religion_id = $request->religion_id;
             $enroll->student->father_name = $request->father_name;
             $enroll->student->father_phone = $request->father_phone;
             $enroll->student->mother_name = $request->mother_name;
             $enroll->student->registration = $request->registration;
             $enroll->student->dob= $request->dob;
             $enroll->student->updated_by = $user_auth->id; 
       
          
             if ($request->hasFile('profile_picture')) {
                 $this->handleProfilePictureUpload($request, $school);
              }

             $enroll->roll= $request->roll;


             $enroll->user->save();
             $enroll->student->save();
             $enroll->save();

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
