<?php

namespace App\Livewire\Hemodialysis;

use App\Services\AccountJournalServices;
use App\Services\HemoServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use App\Services\ItemTreatmentServices;
use App\Services\ServiceChargeServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InventoryTreatment extends Component
{
    #[Reactive]
    public bool $ActiveRequired;
    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $LOCATION_ID;
    public int $openStatus = 1; // draft default
    public $isDisabled = false;
    public bool $saveSuccess = false;
    public $dataList = [];
    public $subClassList = [];
    private $hemoServices;
    private $itemServices;
    private $unitOfMeasureServices;
    private $itemTreatmentServices;
    private $itemSubClassServices;
    private $serviceChargeServices;
    private $timerServices;
    private $iteminventoryServices;
    private $accountJournalServices;
    public function boot(
        HemoServices $hemoServices,
        ItemServices $itemServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemTreatmentServices $itemTreatmentServices,
        ItemSubClassServices $itemSubClassServices,
        ServiceChargeServices $serviceChargeServices,
        AccountJournalServices $accountJournalServices,
        ItemInventoryServices $iteminventoryServices
    ) {
        $this->hemoServices = $hemoServices;
        $this->itemServices = $itemServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->itemSubClassServices = $itemSubClassServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->iteminventoryServices = $iteminventoryServices;
    }

    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public bool $codeBase;
    public $itemCodeList = [];
    public $itemDescList = [];
    public int $ITEM_ID = 0;
    public float $QUANTITY;
    public int $UNIT_ID = 0;
    public $unitList = [];
    public $editUnitList = [];
    public bool $IS_NEW;
    public $ItemRequiredList = [];

    public bool $CAN_BE_EDIT = false;
    public bool $CAN_BE_DELETE = false;
    public function mount()
    {
        $this->codeBase = false;
        $this->updatedcodeBase();
    }
    public function updatedItemId()
    {
        $this->UNIT_ID = 0;
        $this->QUANTITY = 1;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->unitList = [];
        $this->IS_NEW = true;
        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;
                $this->UNIT_ID = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 1;
            }
        }
    }

    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemTreatmentServices->getItemList(true, $this->LOCATION_ID);
            return;
        }
        $this->itemDescList = $this->itemTreatmentServices->getItemList(false, $this->LOCATION_ID);
    }
    public function deleteItem(int $ID, int $ITEM_ID)
    {
        DB::beginTransaction();
        try {
            $this->hemoServices->ItemDelete($ID, $this->HEMO_ID, $ITEM_ID, true);
            $this->hemoServices->ItemDeleteTrigger($ID, $this->HEMO_ID);
            $this->iteminventoryServices->RecomputedOnhand($ITEM_ID, $this->LOCATION_ID, $this->hemoServices->get($this->HEMO_ID)->DATE);
            session()->flash('message', 'Successfully deleted');
            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }


    }
    public function deleteItemInCash(int $ID, int $ITEM_ID)
    {
        $data = $this->hemoServices->ItemGet($ID);
        if ($data) {
            DB::beginTransaction();
            try {
                if ($data->SC_ITEM_ID > 0) {
                    $dataItem = $this->serviceChargeServices->getItem($data->SC_ITEM_ID);
                    if ($dataItem) {
                        if ($dataItem->PAID_AMOUNT > 0) {
                            session()->flash('error', 'Delete action cannot proceed. This item has already been paid.');
                            return;
                        }
                        $SC_ID = $dataItem->SERVICE_CHARGES_ID;
                        $this->serviceChargeServices->ItemDelete($dataItem->ID, $SC_ID);
                        $this->serviceChargeServices->ReComputed($SC_ID);
                    }
                }

                $this->hemoServices->ItemDelete($ID, $this->HEMO_ID, $ITEM_ID, false);
                $this->hemoServices->ItemDeleteTrigger($ID, $this->HEMO_ID);
                $this->iteminventoryServices->RecomputedOnhand($ITEM_ID, $this->LOCATION_ID, $this->hemoServices->get($this->HEMO_ID)->DATE);
                DB::commit();
                session()->flash('message', 'Successfully deleted');
            } catch (\Throwable $th) {
                DB::rollBack();
                session()->flash('error', $th->getMessage());
            }
        }
    }

    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID' => 'required|not_in:0',
                'QUANTITY' => 'required|not_in:0',
            ],
            [],
            [
                'ITEM_ID' => 'Item',
                'QUANTITY' => 'Quantity'
            ]
        );
        $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails(
            $this->ITEM_ID,
            $this->UNIT_ID ?? 0
        );
        $this->hemoServices->ItemStore(
            $this->HEMO_ID,
            $this->ITEM_ID,
            $this->QUANTITY,
            $this->UNIT_ID,
            (float) $unitRelated['QUANTITY'],
            $this->IS_NEW,
            true
        );
        $this->resetInsert();
        session()->flash('message', 'Successfully added');
    }
    private function resetInsert()
    {

        $this->ITEM_ID = 0;
        $this->QUANTITY = 0;
        $this->UNIT_ID = 0;
        $this->IS_NEW = false;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->updatedcodeBase();
        $this->saveSuccess = $this->saveSuccess ? false : true;
    }
    public $lineId = null;
    public int $lineItemId = 0;
    public float $lineQty;
    public int $lineUnitId;
    public bool $lineIsNew;

    public function editItem(int $editId)
    {

        $data = $this->hemoServices->ItemGet($editId);
        if ($data) {
            $this->lineId = $data->ID;
            $this->lineItemId = $data->ITEM_ID;
            $this->lineUnitId = $data->UNIT_ID ?? 0;
            $this->lineQty = $data->QUANTITY ?? 0;
            $this->lineIsNew = $data->IS_NEW;
            if ($this->lineItemId > 0) {
                $this->editUnitList = $this->unitOfMeasureServices->ItemUnit($this->lineItemId);
            }
        }
    }

    public function cancelItem()
    {
        $this->lineId = null;
        $this->lineItemId = 0;
        $this->lineUnitId = 0;
        $this->lineQty = 0;
        $this->lineIsNew = false;
    }

    public function updateItem()
    {
        $this->validate(
            [
                'lineQty' => 'required|not_in:0',
            ],
            [],
            [
                'lineQty' => 'Quantity'
            ]
        );

        $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId ?? 0);

        $this->hemoServices->ItemUpdate(
            $this->lineId,
            $this->HEMO_ID,
            $this->lineItemId,
            $this->lineQty,
            $this->lineUnitId,
            (float) $unitRelated['QUANTITY'],
            $this->lineIsNew,
            true
        );

        session()->flash('message', 'Successfully updated');
        $this->cancelItem();
    }


    public function loadItemRequired()
    {
        if ($this->ActiveRequired) {
            $this->ItemRequiredList = $this->itemTreatmentServices->getItemRequired($this->LOCATION_ID, $this->HEMO_ID);
        }
    }
    public function addItem(int $ItemTreatmentId)
    {
        $data = $this->itemTreatmentServices->Get($ItemTreatmentId);
        if ($data) {
            $gotNew = true;
            try {
                $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($data->ITEM_ID, $data->UNIT_ID ?? 0);
                $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];

                if (
                    $this->hemoServices->ItemStoreExists(
                        $this->HEMO_ID,
                        $data->ITEM_ID,
                        $data->QUANTITY,
                        $data->UNIT_ID ?? 0,
                        $UNIT_BASE_QUANTITY,
                        $gotNew,
                        true
                    )
                ) {
                    $this->dispatch('refresh-item-treatment');
                    session()->flash('error', 'Item already exists');
                    return;
                }
                $this->hemoServices->ItemStore($this->HEMO_ID, $data->ITEM_ID, $data->QUANTITY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew, true);
                $dataTrigger = $this->itemTreatmentServices->listItemTrigger($ItemTreatmentId);
                foreach ($dataTrigger as $list) {
                    $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                    $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                    $this->hemoServices->ItemStore($this->HEMO_ID, $list->ITEM_ID, $list->QUANTITY, $list->UNIT_ID ?? 0, $TR_UNIT_BASE_QUANTITY, true, true);
                }

                $this->dispatch('refresh-item-treatment'); //refrest item
            } catch (\Throwable $th) {
                session()->flash('error', $th->getMessage());
            }
        }
    }
    public function OpenUsageHistory(int $ITEM_ID)
    {
        $data = $this->hemoServices->get($this->HEMO_ID);
        if ($data) {
            $result = [
                'DATE' => $data->DATE,
                'LOCATION_ID' => $data->LOCATION_ID,
                'ITEM_ID' => $ITEM_ID,
                'CONTACT_ID' => $data->CUSTOMER_ID
            ];

            $this->dispatch('usage-modal-open', result: $result);
        }
    }
    public function rePost(int $ID)
    {
        $this->hemoServices->updateIsPost($ID, $this->HEMO_ID);
    }
    public function openSubClass(int $SUB_ID)
    {
        $isRequiredItemAdded = $this->itemTreatmentServices->getRequiredSuccess($this->LOCATION_ID, $this->HEMO_ID);
        if (!$isRequiredItemAdded) {
            session()->flash('error', ' You must select either a CVC Kit or an AVF Kit before adding other charges.');
            return;
        }

        $data = ['SUB_CLASS_ID' => $SUB_ID];
        $this->dispatch('open-list-sub-item', result: $data);
    }
    public function gotJournal()
    {

        $this->hemoServices->getMakeJournal($this->HEMO_ID);
        $this->gotInventory();

    }
    public function gotInventory()
    {
        $this->hemoServices->makeItemInventory($this->HEMO_ID);
    }
    public function OpenJournal()
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->hemoServices->object_type_hemo, $this->HEMO_ID);

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    #[On('refresh-item-treatment')]
    public function render()
    {
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);
        $this->dataList = $this->hemoServices->ItemView($this->HEMO_ID);
        $this->subClassList = $this->itemSubClassServices->ListHemo();
        $this->loadItemRequired();
        return view('livewire.hemodialysis.inventory-treatment');
    }
}
