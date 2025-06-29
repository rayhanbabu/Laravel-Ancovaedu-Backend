<?php

namespace App\Services\SchoolAdminService;

use App\Models\User;
use App\Models\School;
use App\Models\User_role;
use App\Http\Resources\SchoolAdminResource;
use Illuminate\Http\Request;

class SchoolAdminList
{
   
public function handle(Request $request)
    {
        $query = School::query();
        $user_auth = user();
        $query->select('schools.*')->with('agent')->with('user:id,name,email,phone,username,profile_picture,status');

        if($user_auth->user_role->role_type=='Agent'){
            $query->where('schools.agent_user_id',$user_auth->id);
          }

         if($user_auth->user_role->role_type=='Manager' || $user_auth->user_role->role_type=='Supperadmin'){
                if ($request->has('agent_user_id')) {
                    $query->where('agent_user_id', $request->agent_user_id);
               }
          }
            
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Bangla_name', 'like', "%$search%")
                    ->orWhere('english_name', 'like', "%$search%")
                    ->orWhere('eiin', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
                            ->orWhere('username', 'like', "%$search%");
                    })
                    ->orWhereHas('agent', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('username', 'like', "%$search%");
                    });



            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // View By Id
        if ($request->has('viewById')) {
            $query->where('id', $request->viewById);
        }

        // Sorting
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        $perPage = ($perPage > 100) ? 100 : $perPage; // Max 100 per page

        // Apply pagination
        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => SchoolAdminResource::collection($result),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
        ]);
    }
}
