<?php

namespace App\Services\AttendanceService;

use App\Models\Attendance;
use App\Models\Classdate;
use Illuminate\Http\Request;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\AttendanceListResource;

class AttendanceList
{
   
   public function handle(Request $request,$school_username)
     {
       

        
    // Search
    if ($request->has('student_id') ) {
        $student = $request->student_id;
        $query = Attendance::query();
        $query->with('classdate','student');
        $query->select('attendances.*')->with('classdate.sessionyear','classdate.programyear','classdate.level','classdate.faculty','classdate.department','classdate.section','classdate.subject');
        $query->where('school_username', $school_username);
        $query->where('student_id', $request->student_id);


        $query->whereHas('classdate', function ($q) use ($request, $school_username) {
            $q->where('sessionyear_id', $request->sessionyear_id);
            $q->where('programyear_id', $request->programyear_id)
                ->where('level_id', $request->level_id)->where('faculty_id', $request->faculty_id)
                ->where('department_id', $request->department_id)->where('section_id', $request->section_id);
        });

    if ($request->has('subject_id')) {    
        $query->whereHas('classdate', function ($q) use ($request) {
            $q->where('subject_id', $request->subject_id);
        });
    }
     
     
    }else{
        $query = Classdate::query();  
      
        $query->with('attendances.student');
        $query->select('classdates.*')->with('sessionyear','programyear','level','faculty','department','section','subject');
        $query->where('school_username', $school_username);
        $query->where('sessionyear_id', $request->sessionyear_id)->where('programyear_id', $request->programyear_id)
        ->where('level_id', $request->level_id)->where('faculty_id', $request->faculty_id)
        ->where('department_id', $request->department_id)->where('section_id', $request->section_id)
        ->where('subject_id', $request->subject_id)->where('date', $request->date);

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
   if ($request->has('student_id') ) {
         $resource= AttendanceListResource::collection($result);
   }else{
         $resource= AttendanceResource::collection($result);
    }
       

        return response()->json([
            'data' =>$result,
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
