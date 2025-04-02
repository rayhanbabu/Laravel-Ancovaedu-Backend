<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('Supperadmin')->group(function () {

            Route::get('/manager', [ManagerController::class, 'manager']);
            Route::post('/manager_add', [ManagerController::class, 'manager_add']);
            Route::get('/manager_view/{id}', [ManagerController::class, 'manager_view']);
            Route::post('/manager_update/{id}', [ManagerController::class, 'manager_update']);
            Route::delete('/manager_delete/{id}', [ManagerController::class, 'manager_delete']);
            Route::post('/manager_status', [ManagerController::class, 'manager_status']);
          
     });

  });

?>