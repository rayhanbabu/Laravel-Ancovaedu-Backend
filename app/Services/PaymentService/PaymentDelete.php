<?php
namespace App\Services\PaymentService;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Paymentinvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PaymentDelete
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::findOrFail($id);

            if($payment->collection_type=="Full"){
    
            $paymentinvoices = Paymentinvoice::where('school_username', $school_username)
                ->where('payment_id', $payment->id)
                ->get();
    
            foreach ($paymentinvoices as $row) {
                $invoice = Invoice::find($row->invoice_id);
                if ($invoice) {
                    $invoice->payment_status = 0; // Set unpaid
                    $invoice->save();
                }
            }
    
            // Delete the payment invoice records
            Paymentinvoice::where('school_username', $school_username)
                ->where('payment_id', $payment->id)
                ->delete();
    
            // Delete the payment record
            $payment->delete();
    
            DB::commit();
            return response()->json([
                'message' => 'Payment and related invoices deleted successfully',
                'data' => $payment,
                'paymentinvoices' => $paymentinvoices,
            ], 200);

        }else{
            return response()->json([
                'message' => 'Payment not deleted because it is not full payment',
            ], 200);
        }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
   
}
