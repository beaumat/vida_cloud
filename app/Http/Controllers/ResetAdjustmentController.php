<?php
namespace App\Http\Controllers;

use App\Services\AccountJournalServices;
use App\Services\InventoryAdjustmentServices;
use Illuminate\Support\Facades\DB;

class ResetAdjustmentController extends Controller
{
    private $inventoryAdjustmentServices;
    private $accountJournalServices;

    public function __construct(InventoryAdjustmentServices $inventoryAdjustmentServices, AccountJournalServices $accountJournalServices)
    {

        $this->inventoryAdjustmentServices = $inventoryAdjustmentServices;
        $this->accountJournalServices      = $accountJournalServices;
    }

    public function update($id)
    {
        $data = $this->inventoryAdjustmentServices->Get($id);
        if ($data) {
            // must data exists

            $LOCATION_ID = $data->LOCATION_ID; // Data Location
            $DATE        = $data->DATE;        // Data Date
            try {
                $invAdjustment      = (int) $this->inventoryAdjustmentServices->object_type_map_inventory_adjustment;
                $invAdjustmentItems = (int) $this->inventoryAdjustmentServices->object_type_map_inventory_adjustmentItems;

                $JOURNAL_NO = $this->accountJournalServices->getRecord($invAdjustment, $id);
                if ($JOURNAL_NO == 0) {
                    $JOURNAL_NO = $this->accountJournalServices->getJournalNo($invAdjustment, $id) + 1;
                }
                DB::beginTransaction();
                //Main
                $dataSet = $this->inventoryAdjustmentServices->getInventoryAdjustmentJournal($id);
                $this->accountJournalServices->JournalExecute($JOURNAL_NO, $dataSet, $LOCATION_ID, $invAdjustment, $DATE);

                //Item
                $dataSetItem = $this->inventoryAdjustmentServices->getInventoryAdjustmentItemsJournal($id);
                $this->accountJournalServices->JournalExecute($JOURNAL_NO, $dataSetItem, $LOCATION_ID, $invAdjustmentItems, $DATE);
                $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

                $debit_sum  = (float) $data['DEBIT'];
                $credit_sum = (float) $data['CREDIT'];

                if ($debit_sum == $credit_sum) {
                    DB::commit();
                    return response()->json(['message' => 'Inventory adjustment reset successfully.'], 200);
                }
                DB::rollBack();
                // If the debit and credit sums do not match, rollback the transaction
                return response()->json(['error' => 'Debit and credit sums do not match.'], 400);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMessage = 'Error occurred: [jd]' . $e->getMessage();
                session()->flash('error', $errorMessage);
                \Log::error($errorMessage);

                return response()->json(['error' => $errorMessage], 404);

            }
        }
    }
}
