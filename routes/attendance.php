<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\Attendance\AttendanceController;

    Route::middleware('auth:sanctum')->group(function () {
       Route::middleware('Supperadmin')->group(function () {
            Route::get('/{school_username}/attendance', [AttendanceController::class, 'attendance']);
            Route::post('/{school_username}/attendance-add', [AttendanceController::class, 'attendance_add']);
            Route::post('/{school_username}/attendance-update', [AttendanceController::class, 'attendance_update']);
            Route::delete('/{school_username}/attendance-delete/{id}', [AttendanceController::class, 'attendance_delete']);
        });
    });

?>