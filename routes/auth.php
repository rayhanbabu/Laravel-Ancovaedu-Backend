<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


 Route::post('/login', [AuthController::class, 'login']);

 Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/{school_user}/school-profile', [AuthController::class, 'school_profile']);
 });


?>