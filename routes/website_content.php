<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\WebsiteContent\GpaCategoryController;
use App\Http\Controllers\SchoolPanel\WebsiteContent\GpaListController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('SettingMiddleware:{school_username}')->group(function () {

            Route::get('/{school_username}/gpacategory', [GpaCategoryController::class, 'gpaCategory']);
            Route::post('/{school_username}/gpacategory-add', [GpaCategoryController::class, 'gpaCategory_add']);
            Route::post('/{school_username}/gpacategory-update/{id}', [GpaCategoryController::class, 'gpaCategory_update']);
            Route::delete('/{school_username}/gpacategory-delete/{id}', [GpaCategoryController::class, 'gpaCategory_delete']);

            Route::get('/{school_username}/gpalist', [GpaListController::class, 'gpaList']);
            Route::post('/{school_username}/gpalist-add', [GpaListController::class, 'gpaList_add']);
            Route::post('/{school_username}/gpalist-update/{id}', [GpaListController::class, 'gpaList_update']);
            Route::delete('/{school_username}/gpalist-delete/{id}', [GpaListController::class, 'gpaList_delete']);

     });

  });

?>