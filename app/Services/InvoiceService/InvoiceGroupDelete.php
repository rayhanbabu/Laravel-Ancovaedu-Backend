<?php
namespace App\Services\InvoiceService;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InvoiceGroupDelete
{
    public function handle(Request $request, $school_username, $fee_id)
   {
      DB::beginTransaction();
       try {
          // Fetch invoices with payment_status 1 or partial_payment > 0 for given school and fee



           $invoices = Invoice::where('school_username', $school_username)
              ->where('fee_id', $fee_id)
               ->where(function ($query) {
                  $query->where('payment_status', 1)
                         ->orWhere('partial_payment', '>', 0);

               })->get();

        if ($invoices->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete invoice(s) with payment status 1 or partial payment',
            ], 400);
        }else{
            Invoice::where('school_username', $school_username)
            ->where('fee_id', $fee_id)
            ->delete();
        }

        // Delete all invoices for this school and fee
       

           DB::commit();
           return response()->json([
                 'message' => 'Invoices deleted successfully',
           ], 200);

       } catch (\Exception $e) {
             DB::rollBack();
              return response()->json([
                'message' => 'Failed to delete invoices',
                'data'=>$invoices,
                 'error' => $e->getMessage(),
              ], 500);
         }
    }

   
}
