<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\Mark\MarkController;

    Route::middleware('auth:sanctum')->group(function () {
       Route::middleware('School:{school_username}')->group(function () {
            Route::get('/{school_username}/mark', [MarkController::class, 'mark']);
            Route::post('/{school_username}/mark-update', [MarkController::class, 'mark_update']);
            Route::post('/{school_username}/mark-final-submit', [MarkController::class, 'mark_final_submit']);
        });
    });

?>