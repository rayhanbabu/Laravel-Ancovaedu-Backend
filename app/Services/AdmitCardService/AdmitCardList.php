<?php

namespace App\Services\AdmitCardService;

use App\Models\Admitcard;
use Illuminate\Http\Request;
use App\Http\Resources\AdmitCardResource;
use Illuminate\Support\Facades\DB;

class AdmitCardList
{
   
   public function handle(Request $request,$school_username)
     {


  if($request->has('GroupBySubject') && $request->GroupBySubject==1) {
        $query = Admitcard::query();
         $query->where('school_username', $school_username);
         $query->with([
           'sessionyear:id,sessionyear_name',
           'programyear:id,programyear_name',
           'level:id,level_name',
           'faculty:id,faculty_name',
           'department:id,department_name',
           'section:id,section_name',
        ]); 
      

       $filters = [
        'sessionyear_id',
        'programyear_id',
        'level_id',
        'faculty_id',
        'department_id',
        'section_id',
      ];

   foreach ($filters as $requestKey => $dbColumn) {
       // if $filters is associative, otherwise key = value
       if (is_int($requestKey)) $requestKey = $dbColumn;
       if ($request->filled($requestKey)) {
           $query->where($dbColumn, $request->$requestKey);
       }
   }

     $query->select('admitcard_group', DB::raw('COUNT(*) as total_subject'),
          DB::raw('MAX(sessionyear_id) as sessionyear_id'),
          DB::raw('MAX(programyear_id) as programyear_id'),
            DB::raw('MAX(level_id) as level_id'),
            DB::raw('MAX(faculty_id) as faculty_id'),
            DB::raw('MAX(department_id) as department_id'),
            DB::raw('MAX(section_id) as section_id'))
        ->groupBy('admitcard_group')
         ->orderBy($request->get('sortField', 'id'), $request->get('sortDirection', 'asc'));

     $result = $query->get();

    return response()->json([
       'data' => $result
     ]);

         }


        $query = Admitcard::query();  
         $query->where('school_username', $school_username);
        $query->with('subject:id,subject_name,subject_code'); // Eager load the subject relationship
          $query->with([
           'sessionyear:id,sessionyear_name',
           'programyear:id,programyear_name',
           'level:id,level_name',
           'faculty:id,faculty_name',
           'department:id,department_name',
           'section:id,section_name',
        ]); 
      


           // Apply filters
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
       // if $filters is associative, otherwise key = value
       if (is_int($requestKey)) $requestKey = $dbColumn;
       if ($request->filled($requestKey)) {
           $query->where($dbColumn, $request->$requestKey);
       }
   }


       if ($request->has('admitcard_group')) {
                $query->where('admitcard_group', $request->admitcard_group);
         }
        
    // Search
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('subject_id', 'like', "%$search%")
                ->orWhere('date', 'like', "%$search%")
                ->orWhere('time', 'like', "%$search%");
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
                'data' =>$result->items(),
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem()
        ]);
    }
}
