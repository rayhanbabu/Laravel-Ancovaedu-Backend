<?php

namespace App\Services\PageService;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;

class PageList
{

    public function handle(Request $request,$school_username) {
        $query = Page::query();
        $query->with('pageCategory');
        $query->where('school_username', $school_username);

    $filters = [
        'viewById' => 'id',
        'page_category_id',
    ];

      foreach ($filters as $requestKey => $dbColumn) {
         if (is_int($requestKey)) $requestKey = $dbColumn;
         if ($request->filled($requestKey)) {
             $query->where($dbColumn, $request->$requestKey);
          }
       }
   
      if ($request->has('search')) {
          $search = $request->search;
          $query->where(function ($q) use ($search) {
               $q->where('title', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                   ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%");

              });
         }

       if ($request->has('status')) {
           $query->where('status', $request->status);
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
        ]);
    }
}
