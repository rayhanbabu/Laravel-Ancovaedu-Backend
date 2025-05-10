<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\Student\StudentController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('School:{school_username}')->group(function () {

            Route::get('/{school_username}/student', [StudentController::class, 'student']);
            Route::post('/{school_username}/student-add', [StudentController::class, 'student_add']);
            Route::get('/{school_username}/student-view/{id}', [StudentController::class, 'student_view']);
            Route::post('/{school_username}/student-update/{id}', [StudentController::class, 'student_update']);
            Route::delete('/{school_username}/student-delete/{id}', [StudentController::class, 'student_delete']);
            Route::post('/{school_username}/student-group-delete', [StudentController::class, 'student_group_delete']);


            // english_name, bangla_name, phone, email, gender, relgion_id,roll  excel Sheet
            Route::post('/{school_username}/student-import', [StudentController::class, 'student_import']);
            Route::post('/{school_username}/student-transfer', [StudentController::class, 'student_transfer']);
            Route::post('/{school_username}/student-subject/{id}', [StudentController::class, 'student_subject']);
            Route::post('/{school_username}/student-mark', [StudentController::class, 'student_mark']);
            Route::post('/{school_username}/mark-delete', [StudentController::class, 'mark_delete']);



     });

  });

?>