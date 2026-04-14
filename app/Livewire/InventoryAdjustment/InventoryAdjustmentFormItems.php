<?php

namespace App\Livewire\InventoryAdjustment;

use App\Services\AccountJournalServices;
use App\Services\InventoryAdjustmentServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\PriceLevelLineServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InventoryAdjustmentFormItems extends Component
{

    #[Reactive()]
    public int $INVENTORY_ADJUSTMENT_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $openStatus;
    #[Reactive]
    public string $DATE;
    #[Reactive]
    public int $LOCATION_ID;
    public int $ID;
    public int $ITEM_ID = 0;
    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public float $QUANTITY;
    public float $UNIT_COST;
    public int $UNIT_ID;
    public int $ACCOUNT_ID;
    public float $ASSET_ACCOUNT_ID;

    public int $BATCH_ID;
    public $itemList = [];
    public $editItemId = null;
    public bool $codeBase = false;
    public $itemDescList = [];
    public $itemCodeList = [];
    public $unitList = [];
    public $saveSuccess;
    public float $lineQty;
    public float $lineUnitCost;
    public int $lineUnitId;
    public int $lineBatchId;
    public $editUnitList = [];
    public int $lineItemId = 0;
    private $inventoryAdjustmentServices;
    private $unitOfMeasureServices;
    private $itemServices;
    private $priceLevelLineServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    public function boot(
        InventoryAdjustmentServices $inventoryAdjustmentServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemServices $itemServices,
        PriceLevelLineServices $priceLevelLineServices,
        ItemInventoryServices $itemInventoryServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->inventoryAdjustmentServices = $inventoryAdjustmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemServices = $itemServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->accountJournalServices = $accountJournalServices;
    }

    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getByVendor(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getByVendor(false);
    }
    public function updatedquantity() {


    }
    public function updateditemid()
    {
        $this->UNIT_ID = 0;
        $this->QUANTITY = 1;
        $this->UNIT_COST = 0;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->BATCH_ID = 0;

        $this->unitList = [];

        if ($this->ITEM_ID > 0) {

            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;
                $COST = $this->priceLevelLineServices->GetCostByLocation($this->LOCATION_ID, $this->ITEM_ID);
                $this->UNIT_COST =  $COST > 0 ? $COST  : $item->COST ?? 0;
                $this->UNIT_ID = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 0;
                $this->ASSET_ACCOUNT_ID = $item->ASSET_ACCOUNT_ID ?? 0;
            }
        }
    }
    public function mount()
    {
        $this->QUANTITY = 0;
        $this->UNIT_COST = 0;
        $this->updatedcodeBase();
    }
    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID' => 'required|not_in:0|exists:item,id',
                'QUANTITY' => 'required|numeric',
            ],
            [],
            [
                'ITEM_ID' => 'Item',
                'QUANTITY' => 'Quantity',
            ]
        );

        try {

            if ($this->inventoryAdjustmentServices->ItemHasAdjustmentThatBefore($this->ITEM_ID, $this->DATE, $this->LOCATION_ID)) {
                session()->flash('error', 'Item already adjusted that date or greater than.');
                return;
            }

            if ($this->inventoryAdjustmentServices->haveExists($this->INVENTORY_ADJUSTMENT_ID, $this->ITEM_ID)) {
                session()->flash('error', 'Item already added.');
                return;
            }

            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID ?? 0);

            $this->inventoryAdjustmentServices->ItemStore(
                $this->INVENTORY_ADJUSTMENT_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_COST,
                $this->ASSET_ACCOUNT_ID,
                $this->BATCH_ID,
                $this->UNIT_ID,
                (float) $unitRelated['QUANTITY']
            );
            if ($this->UNIT_COST > 0) {
                $this->priceLevelLineServices->SetCostByLocation(
                    $this->LOCATION_ID,
                    $this->ITEM_ID,
                    $this->UNIT_COST
                );
            }

            $this->ITEM_ID = 0;
            $this->QUANTITY = 0;
            $this->UNIT_ID = 0;
            $this->UNIT_COST = 0;
            $this->ITEM_CODE = '';
            $this->ITEM_DESCRIPTION = '';
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->updatedcodeBase();
            $this->dispatch('update-amount');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updatedlineqty()
    {
        $this->getEditAmount();
    }
    public function updatedlinerate()
    {
        $this->getEditAmount();
    }
    public function getEditAmount() {}
    public function editItem(int $ID)
    {
        $data = $this->inventoryAdjustmentServices->GetItem(
            $ID,
            $this->INVENTORY_ADJUSTMENT_ID
        );

        if ($data) {
            $this->editItemId =     $data->ID;
            $this->lineQty =        $data->QUANTITY;
            $this->lineUnitCost =   $data->UNIT_COST ?? 0;
            $this->lineUnitId =     $data->UNIT_ID ?? 0;
            $this->lineItemId =     $data->ITEM_ID;
            $this->lineBatchId =    $data->BATCH_ID ?? 0;
            $this->getEditAmount();
        }
    }

    public function updateItem()
    {
        $this->validate(
            [
                'lineQty' => 'required|numeric',
            ],
            [],
            [
                'lineQty' => 'Quantity',
            ]
        );


        try {
            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId ?? 0);
            $this->inventoryAdjustmentServices->ItemUpdate(
                $this->editItemId,
                $this->INVENTORY_ADJUSTMENT_ID,
                $this->lineItemId,
                $this->lineQty,
                $this->lineUnitCost,
                $this->lineBatchId,
                $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                (float) $unitRelated['QUANTITY']
            );
            $this->cancelItem();
        } catch (\Exception $e) {

            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem()
    {
        $this->editItemId = null;
        $this->lineQty = 0;
        $this->lineUnitId = 0;
        $this->lineItemId = 0;
        $this->lineBatchId = 0;
    }

    public function deleteItem($Id)
    {
        // TO Fix

        DB::beginTransaction();
        try {

            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord($this->inventoryAdjustmentServices->object_type_map_inventory_adjustment, $this->INVENTORY_ADJUSTMENT_ID);
                if ($JOURNAL_NO  ==  0) {
                    session()->flash('message', 'journal not found');
                    return;
                }
                $adjustmentData = $this->inventoryAdjustmentServices->Get($this->INVENTORY_ADJUSTMENT_ID);
                if ($adjustmentData) {
                    $adjustmentItemData = $this->inventoryAdjustmentServices->GetItem($Id, $this->INVENTORY_ADJUSTMENT_ID);
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


            $this->inventoryAdjustmentServices->ItemDelete($Id, $this->INVENTORY_ADJUSTMENT_ID);
            DB::commit();
            $this->dispatch('update-amount');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }


    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function getReload()
    {
        $this->editUnitList = $this->unitOfMeasureServices->ItemUnit($this->lineItemId);
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);
        $this->itemList = $this->inventoryAdjustmentServices->ItemView($this->INVENTORY_ADJUSTMENT_ID);
    }
    public function render()
    {
        $this->getReload();
        return view('livewire.inventory-adjustment.inventory-adjustment-form-items');
    }
}
