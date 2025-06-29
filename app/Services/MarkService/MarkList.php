<?php

namespace App\Services\MarkService;

use App\Models\Mark;
use App\Models\User;
use App\Models\Classdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkList
{
   
   public function handle(Request $request,$school_username)
     {

             if($request->has('GroupBySubject') && $request->GroupBySubject==1) {
                 $query = Mark::query();

                 $query->join('enrolls', 'marks.enroll_id', '=', 'enrolls.id')
                    ->join('subjects', 'marks.subject_id', '=', 'subjects.id') // Added join for subjects
                    ->where('marks.school_username', $school_username)
                    ->where('marks.exam_id', $request->exam_id)
                    ->where('enrolls.sessionyear_id', $request->sessionyear_id)
                    ->where('enrolls.programyear_id', $request->programyear_id)
                    ->where('enrolls.level_id', $request->level_id)
                    ->where('enrolls.faculty_id', $request->faculty_id)
                    ->where('enrolls.department_id', $request->department_id);
                 $query->with('subject','exam:id,exam_name');

                if ($request->has('section_id')) {
                    $query->where('enrolls.section_id', $request->section_id);
                }

                $query->select(
                    'marks.subject_id',
                    DB::raw('COUNT(marks.id) as total_students'),
                    DB::raw('SUM(CASE WHEN marks.gpa > 0 THEN 1 ELSE 0 END) as total_pass'),
                    DB::raw('SUM(marks.attendance_status) as total_attendance'),
                    DB::raw('MAX(marks.mark_group) as mark_group'),
                    DB::raw('MAX(marks.exam_id) as exam_id'),
                    DB::raw('MAX(marks.updated_at) as final_submited_at'),
                    DB::raw('MAX(marks.final_submited_by) as final_submited_by'),
                    DB::raw('MAX(CASE WHEN marks.final_submit_status = 1 THEN 1 ELSE 0 END) as final_submit_status'),
                    'subjects.serial as subject_serial' // Added this to be able to sort by
                )->groupBy('marks.subject_id', 'subjects.serial'); // Important: include any selected non-aggregates here

                // Apply sorting
                $sortField = $request->get('sortField', 'subject_serial'); // sortField should match the alias or actual column name in select
                $sortDirection = $request->get('sortDirection', 'asc');

                $query->orderBy($sortField, $sortDirection);

                // Get results
                $result = $query->get();

                $userNames = User::whereIn('id', $result->pluck('final_submited_by'))->pluck('name', 'id');

                $result->transform(function ($item) use ($userNames) {
                    $item->final_submited_by_name = $userNames[$item->final_submited_by] ?? null;
                    return $item;
                });

                return response()->json([
                    'data' => $result
                ]);
         }


        $query = Mark::query();
        $query->join('enrolls', 'marks.enroll_id', '=', 'enrolls.id');  // Ordering Roll Assingn
        $query->with([
           'student','subject',
           'enroll.sessionyear:id,sessionyear_name',
           'enroll.programyear:id,programyear_name',
           'enroll.level:id,level_name',
           'enroll.faculty:id,faculty_name',
           'enroll.department:id,department_name',
           'enroll.section:id,section_name',
        ]);
        $query->where('marks.school_username', $school_username);
      
         if ($request->has('subject_id')) {
              $query->where('subject_id', $request->subject_id);
          }

          if ($request->has('mark_group')) {
                $query->where('marks.mark_group', $request->mark_group);
          }

         if ($request->has('access_group')) {
                $query->where('marks.mark_group', $request->access_group);
        }


        if ($request->has('final_submited_by')) {
            $query->where('marks.final_submited_by', $request->final_submited_by);
        }

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
            
        $sortField = $request->get('sortField', 'roll');
        $sortDirection = $request->get('sortDirection', 'asc');
        if ($sortField === 'roll') {
            $query->orderBy('enrolls.roll', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $query->select('marks.*','enrolls.roll');

     
        $perPage = (int) $request->input('perPage', 250);
        $page = (int) $request->input('page', 1);
     

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
