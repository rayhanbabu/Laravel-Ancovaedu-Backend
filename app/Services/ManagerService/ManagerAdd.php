<?php

namespace App\Services\ManagerService;

use App\Models\User;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;

class ManagerAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'username' => 'required|unique:users,username',
                'password' => 'required|regex:/^[a-zA-Z\d]*$/|min:6',
                'profile_picture' => 'image|mimes:jpeg,png,jpg|max:600',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

           $user_auth = user();

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->username = $request->username;

            if ($request->hasfile('profile_picture')) {
                $user->profile_picture = $this->uploadFile($request->file('profile_picture'), 'profile_picture');
            }

            $user->save();

            User_role::create([
                'user_id' => $user->id,
                'role_type' => 'Manager',
                'created_by' => $user_auth->id,
            ]);

            DB::commit();

            return response()->json([
                  'message' => 'Manager added successfully',
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
