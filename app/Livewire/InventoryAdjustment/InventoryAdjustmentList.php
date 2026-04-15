<?php
namespace App\Livewire\InventoryAdjustment;

use App\Services\AccountJournalServices;
use App\Services\InventoryAdjustmentServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Inventory Adjustment')]
class InventoryAdjustmentList extends Component
{
    use WithPagination;
    public int $perPage        = 15;
    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public int $locationid;
    public $locationList = [];
    private $inventoryAdjustmentServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    private $itemInventoryServices;
    public function boot(InventoryAdjustmentServices $inventoryAdjustmentServices, LocationServices $locationServices, UserServices $userServices, AccountJournalServices $accountJournalServices, ItemInventoryServices $itemInventoryServices)
    {
        $this->inventoryAdjustmentServices = $inventoryAdjustmentServices;
        $this->locationServices            = $locationServices;
        $this->userServices                = $userServices;
        $this->accountJournalServices      = $accountJournalServices;
        $this->itemInventoryServices       = $itemInventoryServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $mainData = $this->inventoryAdjustmentServices->Get($id);
            if ($mainData) {

                $jn = $this->accountJournalServices->getRecord($this->inventoryAdjustmentServices->object_type_map_inventory_adjustment, $id);

                $itemList = $this->inventoryAdjustmentServices->ItemView($id);

                foreach ($itemList as $item) {
                    $this->deleteItem($item->ID, $id, $mainData->STATUS);
                }

                $this->accountJournalServices->DeleteJournal($mainData->ACCOUNT_ID,
                    $mainData->LOCATION_ID,
                    $jn,
                    $mainData->ADJUSTMENT_TYPE_ID,
                    $id,
                    $this->inventoryAdjustmentServices->object_type_map_inventory_adjustment,
                    $mainData->DATE,
                    0);

                $this->inventoryAdjustmentServices->Delete($id);

            }

            DB::commit();
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function deleteItem(int $Id, int $INVENTORY_ADJUSTMENT_ID, int $STATUS)
    {
        // TO Fix
        if ($STATUS == 16) {
            $JOURNAL_NO = $this->accountJournalServices->getRecord($this->inventoryAdjustmentServices->object_type_map_inventory_adjustment, $INVENTORY_ADJUSTMENT_ID);
            if ($JOURNAL_NO == 0) {
                session()->flash('message', 'journal not found');
                return;
            }
            $adjustmentData = $this->inventoryAdjustmentServices->Get($INVENTORY_ADJUSTMENT_ID);
            if ($adjustmentData) {
                $adjustmentItemData = $this->inventoryAdjustmentServices->GetItem($Id, $INVENTORY_ADJUSTMENT_ID);
                if ($adjustmentItemData) {
                    // Inventory
                    $this->itemInventoryServices->InventoryModify(
                        $adjustmentItemData->ITEM_ID,
                        $adjustmentData->LOCATION_ID,
                        $Id,
                        $this->inventoryAdjustmentServices->documentTypeMapId,
                        $adjustmentData->DATE,
                        0,
                        0,
                        0
                    );
                    // Journal
                    $this->accountJournalServices->DeleteJournal(
                        $adjustmentItemData->ASSET_ACCOUNT_ID,
                        $adjustmentData->LOCATION_ID,
                        $JOURNAL_NO,
                        $adjustmentItemData->ITEM_ID,
                        $Id,
                        $this->inventoryAdjustmentServices->object_type_map_inventory_adjustmentItems,
                        $adjustmentData->DATE,
                        1,

                    );

                }
            }
        }

        $this->inventoryAdjustmentServices->ItemDelete($Id, $INVENTORY_ADJUSTMENT_ID);

    }
    public function render()
    {
        $dataList = $this->inventoryAdjustmentServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.inventory-adjustment.inventory-adjustment-list', ['dataList' => $dataList]);
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
}
