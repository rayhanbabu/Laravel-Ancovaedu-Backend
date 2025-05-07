<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\StudentAccount\FeeController;
use App\Http\Controllers\SchoolPanel\StudentAccount\InvoiceController;
use App\Http\Controllers\SchoolPanel\StudentAccount\PaymentController;

  Route::middleware('auth:sanctum')->group(function () {
      Route::middleware('School:{school_username}')->group(function () {

            Route::get('/{school_username}/fee', [FeeController::class, 'fee']);
            Route::post('/{school_username}/fee-add', [FeeController::class, 'fee_add']);
            Route::post('/{school_username}/fee-update/{id}', [FeeController::class, 'fee_update']);
            Route::delete('/{school_username}/fee-delete/{id}', [FeeController::class, 'fee_delete']);


            Route::get('/{school_username}/invoice', [InvoiceController::class, 'invoice']);
            Route::post('/{school_username}/auto-invoice-add', [InvoiceController::class, 'invoice_add']);
            Route::post('/{school_username}/invoice-single-add', [InvoiceController::class, 'invoice_single_add']);
            Route::post('/{school_username}/invoice-custom-add', [InvoiceController::class, 'invoice_custom_add']);
            Route::delete('/{school_username}/invoice-delete/{id}', [InvoiceController::class, 'invoice_delete']);
            Route::delete('/{school_username}/invoice-group-delete/{id}', [InvoiceController::class, 'invoice_group_delete']);


            Route::get('/{school_username}/payment', [PaymentController::class, 'payment']);
            Route::get('/{school_username}/payment-report', [PaymentController::class, 'payment_report']);
            Route::post('/{school_username}/payment-add', [PaymentController::class, 'payment_add']);
            Route::delete('/{school_username}/payment-delete/{id}', [PaymentController::class, 'payment_delete']);
            Route::delete('/{school_username}/partial-payment-delete/{id}', [PaymentController::class, 'partial_payment_delete']);


     });

  });

?>