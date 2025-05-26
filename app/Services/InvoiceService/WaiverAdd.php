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

class WaiverAdd
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $validator = validator($request->all(),[     
                  'waiver_amount' => 'required|numeric|min:0',
                  'waiver_desc' => 'nullable|string|max:255',
             ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
              }

              $invoice = Invoice::findOrFail($id);

               if($invoice->payment_status == 1) {
                return response()->json([
                    'message' => 'Waiver  cannot be Added for paid invoices',
                ], 400);
               }
               if($invoice->total_amount < $request->waiver_amount) {
                  return response()->json([
                      'message' => 'Waiver amount cannot be greater than total amount',
                  ], 400);
                }
                
              $invoice->waiver_amount = $request->waiver_amount;
              $invoice->waiver_desc = $request->waiver_desc;
              $invoice->waiver_request_by   = $user_auth->id;
              $invoice->save();

            DB::commit();

            return response()->json([
                  'message' => 'Data added successfully',
                    'data' => $invoice
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Add ',
                  'error' => $e->getMessage(),
              ], 500);
        }
    }

    
  }
