<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\AgentController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('Supperadmin')->group(function () {

            Route::get('/agent', [AgentController::class, 'agent']);
            Route::post('/add_agent', [AgentController::class, 'add_agent']);
            Route::get('/agent_view/{id}', [AgentController::class, 'agent_view']);
            Route::post('/update_agent', [AgentController::class, 'update_agent']);
            Route::post('/delete_agent', [AgentController::class, 'delete_agent']);
          
        

     });

  });

?>