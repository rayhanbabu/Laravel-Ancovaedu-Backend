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
        $query->with('classdate','student','enroll');
        $query->select('attendances.*')->with('enroll.sessionyear','enroll.programyear','enroll.level','enroll.faculty','enroll.department','enroll.section','classdate.subject');
        $query->where('school_username', $school_username);
        $query->where('student_id', $request->student_id);

          $query->whereHas('enroll', function ($q) use ($request) {
                    $filterFields = [
                        'sessionyear_id',
                        'programyear_id',
                        'level_id',
                        'faculty_id',
                        'department_id',
                        'section_id'
                    ];

                    foreach ($filterFields as $field) {
                        if ($request->filled($field)) {
                            $q->where($field, $request->$field);
                        }
                    }
                });


      if ($request->has('subject_id')) {    
          $query->whereHas('classdate', function ($q) use ($request) {
              $q->where('subject_id', $request->subject_id);
          });
      }
     
     
    }else{
         $query = Classdate::query(); 
         $query->with('enroll');
         $query->with('attendances.student');
         $query->select('classdates.*')->with('enroll.sessionyear','enroll.programyear','enroll.level','enroll.faculty','enroll.department','enroll.section','subject');
         $query->where('school_username', $school_username);

        if ($request->has('subject_id')) {
              $query->where('subject_id', $request->subject_id);
         }

         if ($request->has('date')) {
              $query->where('date', $request->date);
          }

            if ($request->has('attendance_group')) {
                $query->where('attendance_group', $request->attendance_group);
           }


         $query->whereHas('enroll', function ($q) use ($request) {
                if ($request->has('sessionyear_id')) {
                      $q->where('sessionyear_id', $request->sessionyear_id);
                }

                if ($request->has('programyear_id')) {
                    $q->where('programyear_id', $request->programyear_id);
                }

                if ($request->has('level_id')) {
                    $q->where('level_id', $request->level_id);
                }

                if ($request->has('faculty_id')) {
                    $q->where('faculty_id', $request->faculty_id);
                }

                if ($request->has('department_id')) {
                    $q->where('department_id', $request->department_id);
                }

                if ($request->has('section_id')) {
                    $q->where('section_id', $request->section_id);
                }
              
            });

    }

  
     
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

     
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
      

        $result = $query->paginate($perPage, ['*'], 'page', $page);
   if ($request->has('student_id') ) {
         $resource= AttendanceListResource::collection($result);
   }else{
         $resource= AttendanceResource::collection($result);
    }
       

        return response()->json([
            'data' =>$resource, 
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem()
            
        ]);
    }
}
