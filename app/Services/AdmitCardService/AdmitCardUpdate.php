<?php
namespace App\Services\AdmitCardService;

use Illuminate\Http\Request;
use App\Models\AdmitCard;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class AdmitCardUpdate
{
    public function handle(Request $request)
    
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [     
                'time' => 'required|array',
                'time.*' => 'required|string|max:255',
            
                'date' => 'required|array',
                'date.*' => 'required|date|date_format:Y-m-d',
            
                'subject_id' => 'required|array',
                'subject_id.*' => 'required|integer|exists:subjects,id',

                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                 'section_id' => 'required|integer|exists:sections,id',              
            ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

        

             $subject_ids = array_values(array_filter(array_unique($request->subject_id)));
             $times = array_values(array_filter($request->time));
             $dates = array_values(array_filter($request->date));


             for ($i = 0; $i < count($subject_ids); $i++) {
                $AdmitCard = new AdmitCard();
                $AdmitCard->school_username = $request->school_username;
                $AdmitCard->sessionyear_id = $request->sessionyear_id;
                $AdmitCard->programyear_id = $request->programyear_id;
                $AdmitCard->level_id = $request->level_id;
                $AdmitCard->faculty_id = $request->faculty_id;
                $AdmitCard->department_id = $request->department_id;
                $AdmitCard->section_id = $request->section_id;
                $AdmitCard->subject_id = $subject_ids[$i];
                $AdmitCard->time = $times[$i];
                $AdmitCard->date = $dates[$i];
                $AdmitCard->created_by = $user_auth->id;
                $AdmitCard->save();
            }

                

        


            DB::commit();

            return response()->json([
                  'message' => 'Data added successfully',
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Add ',
                  'error' => $e->getMessage(),
              ], 500);
        }
    }


}
