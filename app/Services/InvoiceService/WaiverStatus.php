<?php

namespace App\Services\InvoiceService;

use App\Models\Invoice;
use App\Models\Fee;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class WaiverStatus
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
         try {
            $user_auth = auth()->user();

            $invoice = Invoice::findOrFail($id);
            if($invoice->payment_status == 1) {
                return response()->json([
                    'message' => 'Waiver status cannot be changed for paid invoices',
                ], 400);
            }

            $waiver_approved_status=$invoice->waiver_approved_status==1 ? $invoice->waiver_approved_status = 0 : $invoice->waiver_approved_status = 1;
            $total_amount=$waiver_approved_status==1 ? $invoice->total_amount - $invoice->waiver_amount : $invoice->total_amount + $invoice->waiver_amount;
            $invoice->waiver_approved_status = $waiver_approved_status;
            $invoice->total_amount = $total_amount;
            $invoice->waiver_approved_by  = $user_auth->id;
            $invoice->save();

            DB::commit();

            return response()->json([
                  'message' => 'Data Status Update successfully',
                  'data' => $invoice
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Update ',  
                  'error' => $e->getMessage(),
              ], 500);
        }
    }

    
  }
