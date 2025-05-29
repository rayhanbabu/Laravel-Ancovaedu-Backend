<?php

namespace App\Services\BalanceService;

use App\Models\User;
use App\Models\Balance;
use App\Http\Resources\BalanceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceList
{
    public function handle(Request $request)
    {
        $school_username = $request->school_username;



          if($request->has('YearlyReport') && $request->YearlyReport==1) {
                 $query = Balance::query();
                 $query->where('school_username', $school_username);
                 $query->where('status', 1); // Only include active balances
                 $query->where('year', $request->year);
                
       
                if ($request->has('category_type')) {
                       $query->where('category_type', $request->category_type);
                }

                $query->select(
                    'balances.month',
                     DB::raw('COUNT(balances.id) as total_number'),
                     DB::raw('SUM(balances.amount) as amount'), 
                 )
                ->groupBy('balances.month'); // Important: include any selected non-aggregates here

                // Apply sorting
                $sortField = $request->get('sortField', 'month'); // sortField should match the alias or actual column name in select
                $sortDirection = $request->get('sortDirection', 'asc');

                $query->orderBy($sortField, $sortDirection);

                // Get results
                $result = $query->get();

                return response()->json([
                    'data' => $result,
                    'total_amount' => $result->sum('amount'),
                    'category_type' => $request->category_type? $request->category_type : null,
                    'year' => $request->year? $request->year : null,
                   
                ]);

         }



            if($request->has('MonthlyReport') && $request->MonthlyReport==1) {
                 $query = Balance::query();
                 $query->where('school_username', $school_username);
                 $query->where('status', 1); // Only include active balances
                 $query->where('year', $request->year);
                 $query->where('month', $request->month);
       
                if ($request->has('category_type')) {
                       $query->where('category_type', $request->category_type);
                }

                $query->select(
                    'balances.day',
                     DB::raw('COUNT(balances.id) as total_number'),
                     DB::raw('SUM(balances.amount) as amount'), 
                 )
                ->groupBy('balances.day'); // Important: include any selected non-aggregates here

                // Apply sorting
                $sortField = $request->get('sortField', 'day'); // sortField should match the alias or actual column name in select
                $sortDirection = $request->get('sortDirection', 'asc');

                $query->orderBy($sortField, $sortDirection);

                // Get results
                $result = $query->get();

                return response()->json([
                    'data' => $result,
                    'total_amount' => $result->sum('amount'),
                    'category_type' => $request->category_type? $request->category_type : null,
                    'year' => $request->year? $request->year : null,
                    'month' => $request->month? $request->month : null,
                ]);

         }




        $query = Balance::query();  
        $query->with(['category:id,category_name','creator:id,name,username' ,'updater:id,name,username' ,'verified:id,name,username']);
        $query->where('school_username', $school_username);
       

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%$search%")
                    ->orWhere('amount', 'like', "%$search%")
                     ->orWhere('date', 'like', "%$search%")
                      ->orWhere('year', 'like', "%$search%");
            });
        }


        if ($request->has('status')) {
              $query->where('status', $request->status);
         }

       
        if ($request->has('category_type')) {
              $query->where('category_type', $request->category_type);
         }
      

     
         if ($request->has('viewById')) {
              $query->where('id', $request->viewById);
         }

      if ($request->has('date_range') && $request->date_range == 1) {
             $startDate = date('Y-m-d', strtotime($request->start_date));
             $endDate = date('Y-m-d', strtotime($request->end_date));

            $query->whereBetween('date', [$startDate, $endDate]);
          }

     if ($request->has('year') && $request->has('month') && $request->has('day')) {
          $year = $request->input('year');
          $month = $request->input('month');
          $day = $request->input('day');

       
          $query->where('date', '=', "$year-$month-$day");
    } elseif ($request->has('year') && $request->has('month')) {
        $year = $request->input('year');
        $month = $request->input('month');

       
        $query->where('year', '=', $year)
              ->where('month', '=', $month);
    } elseif ($request->has('year')) {
        $year = $request->input('year');

        $query->where('year', '=', $year);
    }

        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);


        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
       

        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->items(),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
            'status' => $request->status ?? null,
            'category_type' => $request->category_type ?? null,
            'year' => $request->year ?? null,
            'month' => $request->month ?? null,
            'day' => $request->day ?? null,
            'total_amount' => $result->sum('amount'),
        ]);
    }
}
