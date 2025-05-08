<?php
namespace App\Services\MarkService;

use App\Models\Mark;
use App\Models\Classdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MarkSubmit
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
             $classdate = Classdate::findOrFail($id);
               $Mark = Mark::where('classdate_id', $id)->where('status',1)->where('school_username', $school_username)->get();
                if ($Mark->isEmpty()) {
                    $classdate->Marks()->Submit(); // Submit all Mark records associated with the classdate
                      $classdate->Submit();
                }else {
                    return response()->json([
                        'message' => 'Mark already taken',
                    ], 400);
                }

           
           
            DB::commit();
            return response()->json([
                'message' => 'Agent Submitd successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to Submit agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   
}
