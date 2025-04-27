<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolAdmin\SchoolAdminController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('Supperadmin')->group(function () {

          
            Route::get('/schooladmin', [SchoolAdminController::class, 'schooladmin']);
            Route::post('/schooladmin-add', [SchoolAdminController::class, 'schooladmin_add']);
            Route::get('/schooladmin-view/{id}', [SchoolAdminController::class, 'schooladmin_view']);
            Route::post('/schooladmin-update/{id}', [SchoolAdminController::class, 'schooladmin_update']);
            Route::delete('/schooladmin-delete/{id}', [SchoolAdminController::class, 'schooladmin_delete']);
            Route::post('/schooladmin-status', [SchoolAdminController::class, 'schooladmin_status']);
     });

  });

?>