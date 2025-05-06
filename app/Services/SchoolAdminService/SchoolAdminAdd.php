<?php

namespace App\Services\SchoolAdminService;

use App\Models\User;
use App\Models\School;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;

class SchoolAdminAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'english_name' => 'required',
                'bangla_name' => 'required',
                'email' => 'required|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'eiin' => 'required|unique:schools,eiin',
                'password' => 'required|regex:/^[a-zA-Z\d]*$/|min:6',
                'profile_picture' => 'image|mimes:jpeg,png,jpg|max:600',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $noSpace = str_replace(' ', '', $request->english_name);
            $username = substr($noSpace, 0, 14).$request->eiin;

          
            $user_auth = user();

            $user = new User();
            $user->name = $request->english_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->username = $username;

            if ($request->hasfile('profile_picture')) {
                $user->profile_picture = $this->uploadFile($request->file('profile_picture'), 'profile_picture');
            }

            $user->save();

            User_role::create([
                'user_id' => $user->id,
                'role_type' => 'School',
                'created_by' => $user_auth->id,
            ]);

            $school = new School();
            $school->user_id = $user->id;
            $school->school_username=$user->username;
            $school->eiin = $request->eiin;
            $school->bangla_name = $request->bangla_name;
            $school->english_name = $request->english_name;
            $school->full_address = $request->full_address;
            $school->short_address = $request->short_address;
            $school->save();

            DB::commit();

            return response()->json([
                  'message' => 'School added successfully',
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
