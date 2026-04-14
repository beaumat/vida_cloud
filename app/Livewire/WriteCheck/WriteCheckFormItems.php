<?php

namespace App\Livewire\WriteCheck;

use App\Services\AccountJournalServices;
use App\Services\ComputeServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\PriceLevelLineServices;
use App\Services\TaxServices;
use App\Services\UnitOfMeasureServices;
use App\Services\WriteCheckServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class WriteCheckFormItems extends Component
{

    #[Reactive]
    public int $CHECK_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $TAX_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public string $DATE;
    public int $openStatus = 0;
    public int $ID;
    public int $LINE_NO;
    public int $ITEM_ID = 0;
    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public float $QUANTITY;
    public int $UNIT_ID;
    public float $RATE;
    public int $RATE_TYPE;
    public float $AMOUNT;
    public int $BATCH_ID;
    public int $ACCOUNT_ID;
    public bool $TAXABLE;
    public float $TAXABLE_AMOUNT;
    public float $TAX_AMOUNT;
    public int $CLASS_ID;
    public $itemList = [];
    public $editItemId = null;
    public bool $codeBase = false;
    public $itemDescList = [];
    public $itemCodeList = [];
    public $unitList = [];
    public $saveSuccess;
    public float $lineQty;
    public int $lineUnitId;
    public float $lineRate;
    public float $lineAmount;
    public bool $lineTax;
    public float $lineTaxable;
    public float $lineTaxAmount;
    public $editUnitList = [];
    public int $lineItemId = 0;
    private $writeCheckServices;
    private $computeServices;
    private $unitOfMeasureServices;
    private $taxServices;
    private $itemServices;
    private $accountJournalServices;
    private $itemInventoryServices;
    private $priceLevelLineServices;
    public function boot(
        WriteCheckServices $writeCheckServices,
        ComputeServices $computeServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        TaxServices $taxServices,
        ItemServices $itemServices,
        AccountJournalServices $accountJournalServices,
        ItemInventoryServices $itemInventoryServices,
        PriceLevelLineServices $priceLevelLineServices
    ) {
        $this->writeCheckServices = $writeCheckServices;
        $this->computeServices = $computeServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->taxServices = $taxServices;
        $this->itemServices = $itemServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
    }
    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getByVendor(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getByVendor(false);
    }
    public function getAmount()
    {
        try {
            if ($this->QUANTITY) {
                $qty = $this->QUANTITY > 0 ? $this->QUANTITY : 1;
                $this->AMOUNT = $qty * $this->RATE;
            } else {
                $this->QUANTITY = 1;
                $this->AMOUNT = 0;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function updatedquantity()
    {
        $this->getAmount();
    }
    public function updatedrate()
    {
        $this->getAmount();
    }
    public function updateditemid()
    {
        $this->UNIT_ID = 0;
        $this->QUANTITY = 1;
        $this->RATE = 0;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->TAXABLE = false;
        $this->AMOUNT = 0;
        $this->unitList = [];
        $this->RATE_TYPE = 0;
        $this->BATCH_ID = 0;
        $this->ACCOUNT_ID = false;
        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {

                $this->RATE = $this->priceLevelLineServices->GetCostByLocation($this->LOCATION_ID, $this->ITEM_ID);
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->PURCHASE_DESCRIPTION;
                $this->TAXABLE = $item->TAXABLE;
                $this->ACCOUNT_ID = $item->ASSET_ACCOUNT_ID ?? 0;
                $this->UNIT_ID = $item->BASE_UNIT_ID ?? 0;
                $this->CLASS_ID = 0;
                $this->getAmount();
            }
        }
    }

    public function mount()
    {
        $this->QUANTITY = 0;
        $this->RATE = 0;
        $this->AMOUNT = 0.00;
        $this->updatedcodeBase();
    }
    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID'   => 'required|not_in:0',
                'QUANTITY'  => 'required|numeric|not_in:0',
                'RATE'      => 'required'
            ],
            [],
            [
                'ITEM_ID'   => 'Item',
                'QUANTITY'  => 'Quantitity',
                'RATE'      => 'Cost'
            ]
        );

        $recordExists = (bool) DB::table('bill_items')
            ->where('BILL_ID', '=', $this->CHECK_ID)
            ->where('ITEM_ID', '=', $this->ITEM_ID)
            ->exists();

        if ($recordExists) {
            session()->flash('error', 'Item already exists');
            return;
        }

        DB::beginTransaction();
        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->AMOUNT, $this->TAXABLE, $this->TAX_ID, $taxRate);

            if ($tax_result) {
                $this->TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $this->TAX_AMOUNT = $tax_result['TAX_AMOUNT'];
            }

            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID);
            $this->writeCheckServices->ItemStore(
                $this->CHECK_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_ID > 0 ? $this->UNIT_ID : 0,
                (float) $unitRelated['QUANTITY'],
                $this->RATE,
                $this->RATE_TYPE,
                $this->AMOUNT,
                $this->BATCH_ID,
                $this->ACCOUNT_ID,
                $this->TAXABLE,
                $this->TAXABLE_AMOUNT,
                $this->TAX_AMOUNT,
                $this->CLASS_ID
            );
            $getResult = $this->writeCheckServices->ReComputed($this->CHECK_ID);
            $this->priceLevelLineServices->SetCostByLocation($this->LOCATION_ID, $this->ITEM_ID, $this->RATE);
            DB::commit();

            $this->dispatch('update-amount', result: $getResult);
            $this->resetItem();
            $this->updatedcodeBase();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function resetItem()
    {
        $this->ITEM_ID = 0;
        $this->QUANTITY = 0;
        $this->UNIT_ID = 0;
        $this->RATE = 0;
        $this->RATE_TYPE = 0;
        $this->AMOUNT = 0;
        $this->BATCH_ID = 0;
        $this->ACCOUNT_ID = 0;
        $this->TAXABLE = false;
        $this->TAXABLE_AMOUNT = 0;
        $this->TAX_AMOUNT = 0;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->saveSuccess = $this->saveSuccess ? false : true;
    }

    public function updatedlineqty()
    {
        $this->getEditAmount();
    }
    public function updatedlinerate()
    {
        $this->getEditAmount();
    }
    public function getEditAmount()
    {
        try {
            if ($this->lineQty) {
                $qty = $this->lineQty > 0 ? $this->lineQty : 1;
                $this->lineAmount = $qty * $this->lineRate;
            } else {
                $this->lineQty = 1;
                $this->lineAmount = 0;
            }
        } catch (\Throwable $th) {
        }
    }
    public function editItem(int $lineId, float $lineQty, int $lineUnitId, float $lineRate, float $lineAmount, bool $lineTax, int $itemId)
    {
        $this->editItemId = $lineId;
        $this->lineQty = $lineQty;
        $this->lineUnitId = $lineUnitId;
        $this->lineRate = $lineRate;
        $this->lineAmount = $lineAmount;
        $this->lineTax = $lineTax;
        $this->lineItemId = $itemId;
    }
    public function updateItem(int $Id)
    {
        $this->validate(
            [
                'lineQty' => 'required|numeric|not_in:0',
            ],
            [],
            [
                'lineQty' => 'Quantity',
            ]
        );
        DB::beginTransaction();
        try {

            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->lineAmount, $this->lineTax, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->lineTaxable = $tax_result['TAXABLE_AMOUNT'];
                $this->lineTaxAmount = $tax_result['TAX_AMOUNT'];
            }
            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId);
            $this->writeCheckServices->ItemUpdate($Id, $this->CHECK_ID, $this->lineItemId, $this->lineQty, $this->lineUnitId > 0 ? $this->lineUnitId : 0, (float) $unitRelated['QUANTITY'], $this->lineRate, $this->lineAmount, $this->lineTax, $this->lineTaxable, $this->lineTaxAmount);
            $getResult = $this->writeCheckServices->ReComputed($this->CHECK_ID);
            DB::commit();
            $this->dispatch('update-amount', result: $getResult);
            $this->itemList = $this->writeCheckServices->ItemView($this->CHECK_ID);
            $this->editItemId = null;
            $this->lineQty = 0;
            $this->lineUnitId = 0;
            $this->lineRate = 0;
            $this->lineAmount = 0;
            $this->lineTax = false;
            $this->lineItemId = 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }


    public function deleteItem(int $Id)
    {

        DB::beginTransaction();
        try {
            // delete first

            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->writeCheckServices->object_type_check,
                    $this->CHECK_ID
                );
                if ($JOURNAL_NO  ==  0) {
                    session()->flash('message', 'journal not found');
                    return;
                }
                $checkData = $this->writeCheckServices->get($this->CHECK_ID);
                if ($checkData) {
                    $checkDataItem = $this->writeCheckServices->ItemGet($Id, $this->CHECK_ID,);
                    if ($checkDataItem) {

                        // Inventory
                        $this->itemInventoryServices->InventoryModify(
                            $checkDataItem->ITEM_ID,
                            $checkData->LOCATION_ID,
                            $Id,
                            $this->writeCheckServices->document_type_id,
                            $checkData->DATE,
                            0,
                            0,
                            0
                        );

                        // ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $checkDataItem->ACCOUNT_ID,
                            $checkData->LOCATION_ID,
                            $JOURNAL_NO,
                            $checkDataItem->ITEM_ID,
                            $Id,
                            $this->writeCheckServices->object_type_check_items,
                            $checkData->DATE,
                            0,
                        );
                    }
                }
            }

            $this->writeCheckServices->ItemDelete($Id, $this->CHECK_ID);
            DB::commit();
            $getResult = $this->writeCheckServices->ReComputed($this->CHECK_ID);
            $this->dispatch('update-amount', result: $getResult);
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
        if ($this->editItemId) {
            $this->editUnitList = $this->unitOfMeasureServices->ItemUnit($this->lineItemId);
        }
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);
        $this->itemList = $this->writeCheckServices->ItemView($this->CHECK_ID);
    }
    public function render()
    {   

        $this->getReload();
        return view('livewire.write-check.write-check-form-items');
    }
}
