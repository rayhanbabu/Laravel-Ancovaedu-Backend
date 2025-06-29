<?php

namespace App\Services\StudentService;

use App\Models\Student;
use App\Models\Enroll;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;

class StudentList
{
   
public function handle(Request $request,$school_username)
    
    {
        $query = Enroll::query();
        $query = Enroll::with([
            'user:id,name,email,phone,username,profile_picture,status',
            'student',
            'religion',
            'sessionyear:id,sessionyear_name',
            'programyear:id,programyear_name',
            'level:id,level_name,level_category',
            'faculty:id,faculty_name',
            'department:id,department_name',
            'section:id,section_name',
            'mainSubject1:id,subject_name',
            'mainSubject2:id,subject_name',
            'mainSubject3:id,subject_name',
            'additionalSubject:id,subject_name',
        ])->where('school_username', $school_username);
              
        $query->where('school_username', $school_username);

    $filters = [
        'sessionyear_id',
        'programyear_id',
        'level_id',
        'faculty_id',
        'department_id',
        'section_id',
        'viewById' => 'id'
    ];

    foreach ($filters as $requestKey => $dbColumn) {
        if (is_int($requestKey)) $requestKey = $dbColumn;
        if ($request->filled($requestKey)) {
            $query->where($dbColumn, $request->$requestKey);
        }
    }


     if ($request->has('access_group')) {
                $query->where('enroll_group', $request->access_group);
        }

     if ($request->has('enroll_group')) {
                $query->where('enroll_group', $request->enroll_group);
          }

    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('roll', 'like', "%$search%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%");
                })
                ->orWhereHas('student', function ($q) use ($search) {
                    $q->where('Bangla_name', 'like', "%$search%")
                     ->orWhere('id', 'like', "%$search%")
                        ->orWhere('english_name', 'like', "%$search%");
                });



        });
    }

    if ($request->has('status')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    }

    
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

      
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
      

        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => StudentResource::collection($result),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
        ]);
    }
}
