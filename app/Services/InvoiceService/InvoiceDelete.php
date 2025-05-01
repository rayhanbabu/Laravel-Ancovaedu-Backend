<?php
namespace App\Services\InvoiceService;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InvoiceDelete
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
             $Invoice = Invoice::findOrFail($id);
              if ($Invoice->payment_status == 1) {
                  return response()->json([
                     'message' => 'Cannot delete invoice with payment status 1',
                   ], 400);
               }
             $Invoice->delete();
           
            DB::commit();
            return response()->json([
                'message' => 'Agent deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   
}
