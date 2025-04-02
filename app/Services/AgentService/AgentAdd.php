<?php

namespace App\Services\AgentService;

use App\Models\User;
use App\Models\User_role;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;

class AgentAdd
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
                'password' => 'required|regex:/^[a-zA-Z\d]*$/',
                'nid_front_image' => 'image|mimes:jpeg,png,jpg|max:600',
                'nid_back_image' => 'image|mimes:jpeg,png,jpg|max:600',
                'profile_picture' => 'image|mimes:jpeg,png,jpg|max:600',
                'address' => 'required',
                'district' => 'required',
                'upazila' => 'required',
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
                'role_type' => 'Agent',
                'created_by' => $user_auth->id,
            ]);

            $agent = new Agent();
            $agent->user_id = $user->id;
            $agent->address = $request->address;
            $agent->district = $request->district;
            $agent->upazila = $request->upazila;
            $agent->account_name = $request->account_name;
            $agent->account_number = $request->account_number;
            $agent->bank_name = $request->bank_name;
            $agent->branch_name = $request->branch_name;
            $agent->swift_code = $request->swift_code;
            $agent->routing_number = $request->routing_number;
            $agent->bkash_number = $request->bkash_number;
            $agent->rocket_number = $request->rocket_number;
            $agent->nagad_number = $request->nagad_number;

            if ($request->hasfile('nid_front_image')) {
                $agent->nid_front_image = $this->uploadFile($request->file('nid_front_image'), 'nid_front_image');
            }

            if ($request->hasfile('nid_back_image')) {
                $agent->nid_back_image = $this->uploadFile($request->file('nid_back_image'), 'nid_back_image');
            }

            $agent->save();
            DB::commit();

            return response()->json([
                'message' => 'Agent added successfully',
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
