<?php
namespace App\Services\ManagerService;

use App\Models\User_role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class ManagerUpdate
{
    public function handle($request, $id)
    {

        DB::beginTransaction();
        try {

            $role = User_role::findOrFail($id);

            $validator = validator($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email,' . $role->user_id,
                'phone' => 'required|unique:users,phone,' . $role->user_id,
                'username' => 'required|unique:users,username,' . $role->user_id,
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:600',
            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

    
            $role->user->name = $request->name;
            $role->user->email = $request->email;
            $role->user->phone = $request->phone;
        

            if ($request->hasFile('profile_picture')) {
                $this->handleProfilePictureUpload($request, $role);
            }

            $role->user->save();
            $role->save();

            DB::commit();

            return response()->json([
                'message' => 'Role updated successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   
    private function handleProfilePictureUpload($request, $role)
    {
        $path = public_path('uploads/admin') . '/' . $role->user->profile_picture;
        if ($role->user->profile_picture && File::exists($path)) {
            File::delete($path);
        }
        $image = $request->file('profile_picture');
        $fileName = 'profile_picture' . rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/admin'), $fileName);
        $role->user->profile_picture = $fileName;
    }

}
