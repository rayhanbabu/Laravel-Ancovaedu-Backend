<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


   Route::post('/login', [AuthController::class, 'login']);
   Route::post('/forget-password', [AuthController::class, 'forget_password']);
   Route::post('/reset-password/{forget_reset_code}', [AuthController::class, 'reset_password']);

 Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/{school_user}/school-profile', [AuthController::class, 'school_profile']);

    
 });


?>