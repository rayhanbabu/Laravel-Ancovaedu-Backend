<?php
namespace App\Services\AgentService;

use App\Models\Agent;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AgentDelete
{
    public function handle(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $agent = Agent::findOrFail($id);

            // Delete associated images
            $this->deleteImage($agent->nid_front_image);
            $this->deleteImage($agent->nid_back_image);


            $user = User::findOrFail($agent->user_id);
            $this->deleteImage($user->profile_picture);

            // Delete user role
            User_role::where('user_id', $agent->user_id)->delete();

            // Delete agent and user
            $agent->delete();
            $user->delete();

            DB::commit();
            return response()->json([
                'message' => 'Agent deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function deleteImage($image)
    {
        if ($image) {
            $path = public_path('uploads/admin') . '/' . $image;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }


}
