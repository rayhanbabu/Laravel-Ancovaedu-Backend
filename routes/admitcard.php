<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\AdmitCard\AdmitCardController;

    Route::middleware('auth:sanctum')->group(function () {
       Route::middleware('Supperadmin')->group(function () {
            Route::get('/{school_username}/admitcard', [AdmitCardController::class, 'admitcard']);
            Route::post('/{school_username}/admitcard-add', [AdmitCardController::class, 'admitcard_add']);
            Route::post('/{school_username}/admitcard-update', [AdmitCardController::class, 'admitcard_update']);
            Route::delete('/{school_username}/admitcard-delete/{id}', [AdmitCardController::class, 'admitcard_delete']);
        });
    });

?>