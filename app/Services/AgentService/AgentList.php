<?php

namespace App\Services\AgentService;

use App\Models\Agent;
use App\Http\Resources\AgentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Exception;

class AgentList
{
    public function handle(Request $request)
    
    {
        $query = Agent::query();
    
        $query->select('agents.*')->with('user:id,name,email,phone,username,profile_picture,status');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('address', 'like', "%$search%")
                    ->orWhere('upazila', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
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

       
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

      
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        
        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
             'data' => AgentResource::collection($result),
             'total' => $result->total(),
             'per_page' => $result->perPage(),
             'current_page' => $result->currentPage(),
             'last_page' => $result->lastPage(),
        
        ]);
    }
}
