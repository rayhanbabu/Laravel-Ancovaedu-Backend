<?php

namespace App\Services\PageCategoryService;

use App\Models\Pagecategory;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;

class PageCategoryList
{

  public function handle(Request $request, $school_username)
{

       if ($request->filled('ShowByCategorygroup') && $request->ShowByCategorygroup == 1) {
          $query = Pagecategory::with('children')
             ->where('school_username', $school_username)
              ->whereNull('parent_id');  // fetch root categories only
       }else if($request->filled('ShowByAllCategory') && $request->ShowByAllCategory == 1) {
          $query = Pagecategory::with('parent')
              ->where('school_username', $school_username); // fetch child categories only
        } 

 

    // Apply filters
    $filters = [
        'viewById' => 'id',
        'personal_status' => 'personal_status',
    ];

    foreach ($filters as $requestKey => $dbColumn) {
        if (is_int($requestKey)) $requestKey = $dbColumn;
        if ($request->filled($requestKey)) {
            $query->where($dbColumn, $request->$requestKey);
        }
    }

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('page_category_name', 'like', "%$search%")
              ->orWhere('status', 'like', "%$search%");
        });
    }

    // Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Sorting
    $sortField = $request->get('sortField', 'id');
    $sortDirection = $request->get('sortDirection', 'asc');
    $query->orderBy($sortField, $sortDirection);

    // Pagination
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
