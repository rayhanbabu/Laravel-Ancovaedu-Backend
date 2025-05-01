<?php

namespace App\Services\PaymentService;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Paymentinvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PaymentAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $school_username = $request->school_username;

            $validator = validator($request->all(), [     
                  'amount' => 'required|integer',
                  'session_id' => 'required|integer|exists:sessions,id',
                  'programyear_id' => 'required|integer|exists:programyears,id',
                  'level_id' => 'required|integer|exists:levels,id',
                  'faculty_id' => 'required|integer|exists:faculties,id',
                  'department_id' => 'required|integer|exists:departments,id',
                  'section_id' => 'required|integer|exists:sections,id',
                  'invoice_id'        => 'required|array',
                  'invoice_id.*'      => 'integer|exists:invoices,id',      
                  'collection_type' => 'required',       
             ]);

             if($validator->fails()) {
                 return response()->json([
                      'message' => 'Validation failed',
                      'errors' => $validator->errors(),
                   ], 422);
              }

        
            $invoice_ids = $request->invoice_id;
            $invoice_ids = is_array($invoice_ids) ? $invoice_ids : [$invoice_ids];
            $invoice_ids = array_unique($invoice_ids); // Remove duplicates
            $invoice_ids = array_filter($invoice_ids); // Remove null values

            if($request->collection_type == 'Full'){
                $invoice_sum = 0;

                        foreach ($invoice_ids as $invoice_id) {
                            $invoice = Invoice::find($invoice_id); // shortcut for where('id', $id)->first()

                            if (!$invoice) {
                                return response()->json([
                                    'message' => 'Invoice not found: ' . $invoice_id,
                                ], 400);
                            }

                            if ($invoice->payment_status != 0 || $invoice->partial_payment > 0) {
                                return response()->json([
                                    'message' => 'Invoice already paid or partially paid: ' . $invoice_id,
                                ], 400);
                            }

                            $invoice_sum += $invoice->total_amount;
                        }

                  
                   $invoice_total=$invoice_sum;

                   $single_invoice = Invoice::whereIn('id', $invoice_ids)->update([
                      'payment_status' => 1,
                      'updated_by' => $user_auth->id,
                  ]);              
             }else{
                  $count = count($invoice_ids);
                     if($count == 1){
                           $invoice_total=$request->amount;
                           $single_invoice= Invoice::find($invoice_ids[0]);
                           if($single_invoice->payment_status == 1){
                                return response()->json([
                                     'message' => 'Invoice already paid',
                                ], 400);
                            }else if(($single_invoice->total_amount-$single_invoice->partial_payment)<$request->amount){
                                return response()->json([
                                   'message' => 'Partial payment amount is greater than invoice amount',
                                ], 400);
                            }else{
                                    
                               $single_invoice->partial_payment = $single_invoice->partial_payment+$request->amount;
                               $single_invoice->payment_status = ($single_invoice->total_amount-$single_invoice->partial_payment) == 0 ? 1 : 0;
                               $single_invoice->updated_by = $user_auth->id;
                               $single_invoice->update();
                            }
                          
                      }else{
                           return response()->json([
                               'message' => 'Please select one invoice for partial payment',
                           ], 400);
                       }   
               }
             
                 
             
            $gateway_charge = 0;
            $total_amount = $invoice_total + $gateway_charge;
        
            $Payment = new Payment();
            $Payment->school_username = $request->school_username;
            $Payment->session_id = $request->session_id;
            $Payment->programyear_id = $request->programyear_id;
            $Payment->level_id = $request->level_id;
            $Payment->faculty_id = $request->faculty_id;
            $Payment->department_id = $request->department_id;
            $Payment->section_id = $request->section_id;
            $Payment->collection_type = $request->collection_type;
            $Payment->student_id = $request->student_id;
            $Payment->tran_id = Str::random(10);;
            $Payment->amount = $invoice_total;
            $Payment->total_amount = $total_amount;
            $Payment->gateway_charge = $gateway_charge;
            $Payment->Payment_type = 'cash';
            $Payment->date = date('Y-m-d');
            $Payment->year = date('Y');
            $Payment->month = date('m');
            $Payment->day = date('d'); 
            $Payment->created_by = $user_auth->id;
            $Payment->save();

           foreach ($invoice_ids as $invoice_id) {

              Paymentinvoice::Create([
                   'school_username' => $school_username,
                   'invoice_id' => $invoice_id,
                   'payment_id' => $Payment->id]);
              }

            DB::commit();

            return response()->json([
                  'message' => 'Data added successfully',
                    'data' => $single_invoice,
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
