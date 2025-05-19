<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolAdmin\SchoolAdminController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('SupperManager')->group(function () {
            Route::post('/schooladmin-add', [SchoolAdminController::class, 'schooladmin_add']);
            Route::post('/schooladmin-update/{id}', [SchoolAdminController::class, 'schooladmin_update']);
            Route::delete('/schooladmin-delete/{id}', [SchoolAdminController::class, 'schooladmin_delete']);
            Route::post('/schooladmin-status', [SchoolAdminController::class, 'schooladmin_status']);
     });

       Route::middleware('SupperManagerAgent')->group(function () {
            Route::get('/schooladmin', [SchoolAdminController::class, 'schooladmin']);
       });

  });

?>