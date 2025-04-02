<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('Supperadmin')->group(function () {

         

     });

  });

?>