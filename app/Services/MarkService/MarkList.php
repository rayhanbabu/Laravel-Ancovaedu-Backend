<?php

namespace App\Services\MarkService;

use App\Models\Mark;
use App\Models\Classdate;
use Illuminate\Http\Request;
use App\Http\Resources\MarkResource;
use App\Http\Resources\MarkListResource;
use Illuminate\Support\Facades\DB;

class MarkList
{
   
   public function handle(Request $request,$school_username)
     {
       
        // Search
        
         if($request->has('GroupBySubject') && $request->GroupBySubject==1) {

            $query = Mark::query();
            $query->join('enrolls', 'marks.enroll_id', '=', 'enrolls.id')
                  ->where('marks.school_username', $school_username)
                  ->where('marks.exam_id', $request->exam_id);
                $query->with('subject');
            
            // Filter enrolls
            $query->where('enrolls.sessionyear_id', $request->sessionyear_id)
                  ->where('enrolls.programyear_id', $request->programyear_id)
                  ->where('enrolls.level_id', $request->level_id)
                  ->where('enrolls.faculty_id', $request->faculty_id)
                  ->where('enrolls.department_id', $request->department_id);
            
            if ($request->has('section_id')) {
                $query->where('enrolls.section_id', $request->section_id);
            }
            
            $query->select(
                'marks.subject_id',
                DB::raw('COUNT(marks.id) as total_students'),
                DB::raw('SUM(CASE WHEN marks.total > 0 THEN 1 ELSE 0 END) as total_pass'),
                DB::raw('SUM(CASE WHEN marks.final_submit_status = 1 THEN 1 ELSE 0 END) as total_final_submit')
            )
            ->groupBy('marks.subject_id');
           
            
            // Sorting
            $sortField = $request->get('sortField', 'marks.subject_id');
            $sortDirection = $request->get('sortDirection', 'asc');
            $query->orderBy($sortField, $sortDirection);
            
            // Fetch all results
            $result = $query->get();
            
            return response()->json([
                'data'=>$result
            ]);
         }


        $query = Mark::query();
        $query->join('enrolls', 'marks.enroll_id', '=', 'enrolls.id');  // Ordering Roll Assingn
        $query->with([
           'student', 'subject',
           'enroll.sessionyear:id,sessionyear_name',
           'enroll.programyear:id,programyear_name',
           'enroll.level:id,level_name',
           'enroll.faculty:id,faculty_name',
           'enroll.department:id,department_name',
           'enroll.section:id,section_name',
        ]);// keep other relations
        $query->where('marks.school_username', $school_username);
      

           // Subject  Id
         if ($request->has('subject_id')) {
              $query->where('subject_id', $request->subject_id);
          }

            // Exam  Id
         if ($request->has('exam_id')) {
             $query->where('exam_id', $request->exam_id);
          }
    
              $query->whereHas('enroll', function ($q) use ($request) {
                 $filterFields = [
                    'sessionyear_id',
                    'programyear_id',
                    'level_id',
                    'faculty_id',
                    'department_id',
                    'section_id',
                    'student_id',
                 
                ];

                foreach ($filterFields as $field) {
                    if ($request->filled($field)) {
                        $q->where($field, $request->$field);
                    }
                }
            });
                
        // Sorting
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        if ($sortField === 'roll') {
            $query->orderBy('enrolls.roll', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $query->select('marks.*','enrolls.roll');

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
