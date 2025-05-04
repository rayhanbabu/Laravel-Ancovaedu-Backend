<?php

namespace App\Services\EmployeeService;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;

class EmployeeList
{
   
public function handle(Request $request,$school_username) {
        $query = Employee::query();
        $query = Employee::with([
            'user:id,name,email,phone,username,profile_picture,status',
            'religion:id,religion_name',
            'level:id,level_name',
            'faculty:id,faculty_name',
            'department:id,department_name',
            'designation:id,designation_name',
        ])->where('school_username', $school_username);
              
        $query->where('school_username', $school_username);

        // Apply filters
    $filters = [
        'designation_id',
        'religion_id',
        'level_id',
        'faculty_id',
        'department_id',
        'sectionyear_id',
        'viewById' => 'id'
    ];

    foreach ($filters as $requestKey => $dbColumn) {
        // if $filters is associative, otherwise key = value
        if (is_int($requestKey)) $requestKey = $dbColumn;
        if ($request->filled($requestKey)) {
            $query->where($dbColumn, $request->$requestKey);
        }
    }
    // Search
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('bangla_name', 'like', "%$search%")
                ->orWhere('english_name', 'like', "%$search%")
                ->orWhere('index_number', 'like', "%$search%")
                ->orWhere('tin_number', 'like', "%$search%")
                ->orWhere('father_name', 'like', "%$search%")
                ->orWhere('mother_name', 'like', "%$search%")
                ->orWhere('spouse_name', 'like', "%$search%")
                ->orWhere('present_address', 'like', "%$search%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%");
                });
            
        });
    }

    if ($request->has('status')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
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
            'data' => $result,
            'pagination' => [
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem()
            ]
        ]);
    }
}
