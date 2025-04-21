<?php
namespace App\Services\SchoolAdminService;

use App\Models\School;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class SchoolAdminUpdate
{
    public function handle($request, $id)
    {

        DB::beginTransaction();
        try {

            $school = School::findOrFail($id);

            $validator = validator($request->all(), [
                 'english_name' => 'required',
                 'bangla_name' => 'required',
                 'email' => 'required|unique:users,email,' . $school->user_id,
                 'phone' => 'required|unique:users,phone,' . $school->user_id,
                 'eiin' => 'required|unique:schools,eiin,' . $school->id,
                 'full_address' => 'required',
                 'short_address' => 'required',
                 'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:600',
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

    
            $school->user->name = $request->english_name;
            $school->user->email = $request->email;
            $school->user->phone = $request->phone;

            $school->bangla_name = $request->bangla_name;
            $school->english_name = $request->english_name;
            $school->full_address = $request->full_address;
            $school->short_address = $request->short_address;
            $school->eiin = $request->eiin;
            $school->bangla_name_front_size = $request->bangla_name_front_size;
            $school->english_name_front_size = $request->english_name_front_size;
            $school->full_address_front_size = $request->full_address_front_size;
            $school->short_address_front_size = $request->short_address_front_size;

        

            if ($request->hasFile('profile_picture')) {
                $this->handleProfilePictureUpload($request, $school);
            }

            $school->user->save();
            $school->save();

            DB::commit();

            return response()->json([
                'message' => 'school updated successfully',
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
