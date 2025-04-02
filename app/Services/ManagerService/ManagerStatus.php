<?php
namespace App\Services\ManagerService;
use Illuminate\Support\Facades\Exception;

use App\Models\User;

class ManagerStatus
{
    public function handle($request)
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|exists:users,id',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $user->status = $request->status;
            $user->save();
            return response()->json([
                'message' => 'User status updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update agent status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
