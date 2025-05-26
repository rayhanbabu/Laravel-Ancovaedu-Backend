<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\SchoolAccount\CategoryController;
use App\Http\Controllers\SchoolPanel\SchoolAccount\BalanceController;



  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('InstitutionFinanaceMiddleware:{school_username}')->group(function () {

            Route::get('/{school_username}/category', [CategoryController::class, 'category']);
            Route::post('/{school_username}/category-add', [CategoryController::class, 'category_add']);
            Route::post('/{school_username}/category-update/{id}', [CategoryController::class, 'category_update']);
            Route::delete('/{school_username}/category-delete/{id}', [CategoryController::class, 'category_delete']);
        
            Route::post('/{school_username}/balance-add', [BalanceController::class, 'balance_add']);
            Route::post('/{school_username}/balance-update/{id}', [BalanceController::class, 'balance_update']);
            Route::delete('/{school_username}/balance-delete/{id}', [BalanceController::class, 'balance_delete']);       
     });


      Route::middleware('InstitutionFinanaceByVerifyMiddleware:{school_username}')->group(function () {
               Route::get('/{school_username}/balance-status/{id}', [BalanceController::class, 'balance_status']); 
       });


       Route::middleware('InstitutionGroupMiddleware:{school_username}')->group(function () {
               Route::get('/{school_username}/balance', [BalanceController::class, 'balance']);    
       });


  });




?>