<?php
namespace App\Services\AgentService;

use App\Models\Agent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class AgentUpdate
{
    public function handle($request, $id)
    {

        DB::beginTransaction();
        try {
            $agent = Agent::findOrFail($id);

        $validator = validator($request->all(), [
                 'name' => 'required',
                 'email' => 'required|unique:users,email,' . $agent->user_id,
                 'phone' => 'required|unique:users,phone,' . $agent->user_id,
                 'username' => 'required|unique:users,username,' . $agent->user_id,
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

      
            $agent->user->name = $request->name;
            $agent->user->email = $request->email;
            $agent->user->phone = $request->phone;
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

            // Handling file uploads for images
            if ($request->hasFile('nid_front_image')) {
                $this->handleFileUpload($request, 'nid_front_image', $agent);
            }

            if ($request->hasFile('nid_back_image')) {
                $this->handleFileUpload($request, 'nid_back_image', $agent);
            }

            if ($request->hasFile('profile_picture')) {
                $this->handleProfilePictureUpload($request, $agent);
            }

            $agent->user->save();
            $agent->save();

            DB::commit();

            return response()->json([
                'message' => 'Agent updated successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to update agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function handleFileUpload($request, $fileKey, $agent)
    {
        $path = public_path('uploads/admin') . '/' . $agent->$fileKey;
        if ($agent->$fileKey && File::exists($path)) {
            File::delete($path);
        }
        $image = $request->file($fileKey);
        $fileName = $fileKey . rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/admin'), $fileName);
        $agent->$fileKey = $fileName;
    }

    private function handleProfilePictureUpload($request, $agent)
    {
        $path = public_path('uploads/admin') . '/' . $agent->user->profile_picture;
        if ($agent->user->profile_picture && File::exists($path)) {
            File::delete($path);
        }
        $image = $request->file('profile_picture');
        $fileName = 'profile_picture' . rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/admin'), $fileName);
        $agent->user->profile_picture = $fileName;
    }
}
