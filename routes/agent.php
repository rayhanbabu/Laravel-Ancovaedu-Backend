<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\AgentController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('SupperManager')->group(function () {

         

            Route::get('/agent', [AgentController::class, 'agent']);
            Route::post('/add_agent', [AgentController::class, 'add_agent']);
            Route::get('/agent_view/{id}', [AgentController::class, 'agent_view']);
            Route::post('/agent_update/{id}', [AgentController::class, 'agent_update']);
            Route::delete('/agent_delete/{id}', [AgentController::class, 'agent_delete']);
            Route::post('/agent_status', [AgentController::class, 'agent_status']);
          
     });

  });

?>