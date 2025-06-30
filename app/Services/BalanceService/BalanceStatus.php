<?php
namespace App\Services\BalanceService;

use App\Models\Category;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Exception;


class BalanceStatus
{
   public function handle($request, $school_username, $id)
{
    try {
        $user = user();
        // Get the latest active balance record
        $latestBalance = Balance::where('school_username', $school_username)->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->first();

        $previousBalance = $latestBalance ? $latestBalance->balance : 0;
        $previousId = $latestBalance ? $latestBalance->id : null;

        $balance = Balance::findOrFail($id);

        if ($balance->status == 0) {
            $balance->date = date('Y-m-d');
            $balance->year = date('Y');
            $balance->month = date('m');
            $balance->day = date('d'); 
            $balance->previous_balance = $previousBalance;
            $balance->previous_id = $previousId;
            $balance->balance = $balance->category_type == 'Credit'
                ? $previousBalance + $balance->amount
                : $previousBalance - $balance->amount;

            $balance->status = 1;
            $balance->verified_by = $user->id;

        } elseif ($balance->status == 1) {
            if ($latestBalance && $latestBalance->id == $balance->id) {
                $balance->previous_balance = 0;
                $balance->previous_id = null;
                $balance->balance = 0;
                $balance->status = 0;
                $balance->verified_by = $user->id;
            } else {
                return response()->json([
                    'message' => 'Only the latest active balance can be reversed.',
                    'latest_id' => $latestBalance ? $latestBalance->id : null,
                ], 400);
            }
        }

        $balance->save();

        return response()->json([
            'message' => 'Balance status updated successfully.',
            'data' => $balance,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update balance status.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
