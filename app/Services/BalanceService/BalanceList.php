<?php

namespace App\Services\BalanceService;

use App\Models\User;
use App\Models\Balance;
use App\Http\Resources\BalanceResource;
use Illuminate\Http\Request;

class BalanceList
{
    public function handle(Request $request)
    {
        $school_username = $request->school_username;

        $query = Balance::query();  
        $query->with(['creator:id,name,username' ,'updater:id,name,username' ,'verified:id,name,username']);
        $query->where('school_username', $school_username);
       

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%$search%")
                    ->orWhere('amount', 'like', "%$search%")
                     ->orWhere('date', 'like', "%$search%")
                      ->orWhere('year', 'like', "%$search%");
            });
        }

          // View By Id
        if ($request->has('status')) {
              $query->where('status', $request->status);
         }

           // View By Id
        if ($request->has('category_type')) {
              $query->where('category_type', $request->category_type);
         }
      

        // View By Id
        if ($request->has('viewById')) {
              $query->where('id', $request->viewById);
         }

  if ($request->has('year') && $request->has('month') && $request->has('day')) {
         $year = $request->input('year');
         $month = $request->input('month');
         $day = $request->input('day');

        // Filter by date
        $query->where('date', '=', "$year-$month-$day");
    } elseif ($request->has('year') && $request->has('month')) {
        $year = $request->input('year');
        $month = $request->input('month');

        // Filter by month and year
        $query->where('year', '=', $year)
              ->where('month', '=', $month);
    } elseif ($request->has('year')) {
        $year = $request->input('year');

        // Filter by year
        $query->where('year', '=', $year);
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
            'data' => $result->items(),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
        ]);
    }
}
