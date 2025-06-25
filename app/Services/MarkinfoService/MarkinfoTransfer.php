<?php
namespace App\Services\MarkinfoService;

use App\Models\School;
use App\Models\Markinfo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class MarkinfoTransfer
{
    public function handle($request, $school_username)
    {
        DB::beginTransaction();

        try {
              $user_auth = user();

            // Validation rules
            $rules = [
                'sessionyear_id'    => 'required|integer|exists:sessionyears,id',
                'programyear_id'    => 'required|integer|exists:programyears,id',
                'level_id'          => 'required|integer|exists:levels,id',
                'faculty_id'        => 'required|integer|exists:faculties,id',
                'department_id'     => 'required|integer|exists:departments,id',
                'section_id'        => 'required|integer|exists:sections,id',
                'to_sessionyear_id' => 'required|integer|exists:sessionyears,id',
                'to_programyear_id' => 'required|integer|exists:programyears,id',
                'to_level_id'       => 'required|integer|exists:levels,id',
                'to_faculty_id'     => 'required|integer|exists:faculties,id',
                'to_department_id'  => 'required|integer|exists:departments,id',
                'to_section_id'     => 'required|integer|exists:sections,id',
            ];

            // Validate request
            $validator = validator($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Fetch enrolled students based on given criteria
            $markinfos = Markinfo::where([
                    ['school_username', $school_username],
                    ['sessionyear_id', $request->sessionyear_id],
                    ['programyear_id', $request->programyear_id],
                    ['level_id', $request->level_id],
                    ['faculty_id', $request->faculty_id],
                    ['department_id', $request->department_id],
                ])->get();

            if ($markinfos->isEmpty()) {
                return response()->json([
                    'message' => 'No markinfos found for the given criteria',
                ], 404);
            }

              $markinfo_group = $request->to_sessionyear_id."-".$request->to_programyear_id."-".$request->to_level_id
                 ."-".$request->to_faculty_id."-".$request->to_department_id."-".$request->to_section_id;

              $countCreated = 0;

                $exists = Markinfo::where([
                        ['school_username', $school_username],
                        ['sessionyear_id', $request->to_sessionyear_id],
                        ['programyear_id', $request->to_programyear_id],
                        ['level_id', $request->to_level_id],
                        ['faculty_id', $request->to_faculty_id],
                        ['department_id', $request->to_department_id],
                    ])->exists();

                if ($exists) {
                    return response()->json([
                        'message' => 'Markinfo already exists in the target session',
                    ], 400);
                }else{

          foreach ($markinfos as $row) {

            $markinfo = new Markinfo();
            $markinfo->school_username = $request->school_username;
            $markinfo->sessionyear_id = $request->to_sessionyear_id;
            $markinfo->programyear_id = $request->to_programyear_id;
            $markinfo->level_id = $request->to_level_id;
            $markinfo->faculty_id = $request->to_faculty_id;
            $markinfo->department_id = $request->to_department_id;
            $markinfo->section_id = $request->to_section_id;
            $markinfo->markinfo_group = $markinfo_group;
            $markinfo->start = $row->start ?? null;
            $markinfo->end = $row->end ?? null;
            $markinfo->gpa = $row->gpa ?? null;
            $markinfo->grade = $row->grade ?? null;
            $markinfo->gparange = $row->gparange ?? null;
            $markinfo->created_by = $user_auth->id;
            $markinfo->save();

                 $countCreated++; // increment counter

              }
            }

            DB::commit();

            return response()->json([
                 'message' => "$countCreated Markinfos  Added successfully.",
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to transfer students',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }





}
