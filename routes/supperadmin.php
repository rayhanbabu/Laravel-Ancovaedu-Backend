<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('Supperadmin')->group(function () {

           Route::get('/manager_list', [AuthController::class, 'manager_list']);

     });

  });

?>