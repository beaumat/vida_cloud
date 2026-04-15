<?php

namespace App\Livewire\SalesReceipt;

use App\Services\AccountJournalServices;
use App\Services\ComputeServices;
use App\Services\ItemAccountServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use App\Services\PriceLevelLineServices;
use App\Services\SalesReceiptServices;
use App\Services\TaxServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SalesReceiptFormItems extends Component
{
    #[Reactive]
    public int $SALES_RECEIPT_ID;
    #[Reactive()]
    public int $STATUS;
    #[Reactive]
    public int $TAX_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public $accountList = [];
    public $editAccountList   = [];
    public int $lineINCOME_ACCOUNT_ID = 0;

    public int $openStatus = 0;
    public int $ID;
    public int $LINE_NO;
    public int $ITEM_ID = 0;
    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public float $QUANTITY;
    public int $UNIT_ID;
    public int $BASE_UNIT_ID;
    public float $RATE;
    public int $RATE_TYPE;
    public float $AMOUNT;
    public bool $TAXABLE;
    public float $TAXABLE_AMOUNT;
    public float $TAX_AMOUNT;
    public int $COGS_ACCOUNT_ID;
    public int $ASSET_ACCOUNT_ID;
    public int $INCOME_ACCOUNT_ID;

    public int $BATCH_ID;
    public int $GROUP_LINE_ID;
    public bool $PRINT_IN_FORMS;
    public int $PRICE_LEVEL_ID;
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
    public int $lineBatchId;
    public int $linePriceLevelId;
    public $editUnitList = [];
    public int $lineItemId = 0;
    public $CLASS_DESCRIPTION;

    private $salesReceiptServices;
    private $computeServices;
    private $unitOfMeasureServices;
    private $taxServices;
    private $itemServices;
    private $itemSubClassServices;
    private $accountJournalServices;
    private $itemInventoryServices;
    private $priceLevelLineServices;
    private $itemAccountServices;
    public function boot(
        SalesReceiptServices $salesReceiptServices,
        ComputeServices $computeServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        TaxServices $taxServices,
        ItemServices $itemServices,
        ItemSubClassServices $itemSubClassServices,
        AccountJournalServices $accountJournalServices,
        ItemInventoryServices $itemInventoryServices,
        PriceLevelLineServices $priceLevelLineServices,
        ItemAccountServices $itemAccountServices
    ) {
        $this->salesReceiptServices = $salesReceiptServices;
        $this->computeServices = $computeServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->taxServices = $taxServices;
        $this->itemServices = $itemServices;
        $this->itemSubClassServices = $itemSubClassServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->itemAccountServices = $itemAccountServices;
    }

    public function updatedcodeBase()
    {

        $this->AccountLoad();
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getByCustomer(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getByCustomer(false);
    }
    public function getAmount(): void
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
        $this->CLASS_DESCRIPTION = '';
        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {
                $this->RATE = $this->priceLevelLineServices->GetPriceByLocation($this->LOCATION_ID, $this->ITEM_ID);
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;
                $this->TAXABLE = $item->TAXABLE;
                $this->BASE_UNIT_ID = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 1;
                $this->INCOME_ACCOUNT_ID = $item->GL_ACCOUNT_ID ?? 0;
                $this->COGS_ACCOUNT_ID = $item->COGS_ACCOUNT_ID ?? 0;
                $this->ASSET_ACCOUNT_ID = $item->ASSET_ACCOUNT_ID ?? 0;
                $this->BATCH_ID = $item->BATCH_ID ?? 0;
                $this->GROUP_LINE_ID = false;
                $this->PRINT_IN_FORMS = false;
                $this->PRICE_LEVEL_ID = 0;
                $this->getAmount();
                $this->AccountLoad();
                $this->CLASS_DESCRIPTION = $this->itemSubClassServices->GetClassDesc($item->SUB_CLASS_ID);
            }
        }
    }
    public function getGroupPrice(int $item_id)
    {
        try {
            $totalSum = DB::table('item_components')
                ->select(DB::raw('SUM(RATE * QUANTITY) as total'))
                ->where('ITEM_ID', $item_id)
                ->first();

            return $totalSum->total;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public function mount()
    {


        $this->QUANTITY = 0;
        $this->RATE = 0;
        $this->AMOUNT = 0.00;
        $this->updatedcodeBase();
    }

    public bool $reloadAccount = false;
    private function AccountLoad()
    {
        $this->accountList = $this->itemAccountServices->AccountList($this->ITEM_ID);
        $this->reloadAccount = $this->reloadAccount ? false : true;
    }
    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID' => 'required|not_in:0',
                'QUANTITY' => 'required|not_in:0',
                'RATE' => 'required'
            ],
            [],
            [
                'ITEM_ID'   => 'Item',
                'QUANTITY'  => 'Quantity',
                'RATE'      => 'Price'
            ]
        );
        DB::beginTransaction();
        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->AMOUNT, $this->TAXABLE, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $this->TAX_AMOUNT = $tax_result['TAX_AMOUNT'];
            }

            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID ?? 0);

            $this->salesReceiptServices->ItemStore(
                $this->SALES_RECEIPT_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_ID > 0 ? $this->UNIT_ID : 0,
                (float) $unitRelated['QUANTITY'],
                $this->RATE,
                $this->RATE_TYPE,
                $this->AMOUNT,
                $this->TAXABLE,
                $this->TAXABLE_AMOUNT,
                $this->TAX_AMOUNT,
                $this->COGS_ACCOUNT_ID,
                $this->ASSET_ACCOUNT_ID,
                $this->INCOME_ACCOUNT_ID,
                $this->BATCH_ID,
                $this->GROUP_LINE_ID,
                $this->PRINT_IN_FORMS,
                false,
                $this->PRICE_LEVEL_ID
            );

            $getResult = $this->salesReceiptServices->ReComputed($this->SALES_RECEIPT_ID);
            DB::commit();
            $this->dispatch('update-amount', result: $getResult);

            $this->ITEM_ID = 0;
            $this->QUANTITY = 0;
            $this->UNIT_ID = 0;
            $this->RATE = 0;
            $this->RATE_TYPE = 0;
            $this->AMOUNT = 0;
            $this->TAXABLE = false;
            $this->TAXABLE_AMOUNT = 0;
            $this->TAX_AMOUNT = 0;
            $this->ITEM_CODE = '';
            $this->ITEM_DESCRIPTION = '';
            $this->CLASS_DESCRIPTION = '';
            $this->saveSuccess = $this->saveSuccess ? false : true;

            $this->accountList =  [];
            $this->updatedcodeBase();
        } catch (\Exception $e) {
            DB::rollBack();
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
        $data =  $this->salesReceiptServices->ItemGet($lineId, $this->SALES_RECEIPT_ID);
        if ($data) {
            $this->editAccountList = $this->itemAccountServices->AccountList($itemId);
            $this->lineINCOME_ACCOUNT_ID = $data->INCOME_ACCOUNT_ID ?? 0;
        }


        $this->editItemId = $lineId;
        $this->lineQty = $lineQty;
        $this->lineUnitId = $lineUnitId;
        $this->lineRate = $lineRate;
        $this->lineAmount = $lineAmount;
        $this->lineTax = $lineTax;
        $this->lineItemId = $itemId;
        $this->lineBatchId = 0;
        $this->linePriceLevelId = 0;
    }

    public function updateItem(int $Id)
    {

        $this->validate(
            [
                'lineQty' => 'required|not_in:0',
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

            if ($this->STATUS == 16) {
                $dataItem =  $this->salesReceiptServices->ItemGet($Id, $this->SALES_RECEIPT_ID);
                if ($dataItem) {
                    $data = $this->salesReceiptServices->get($this->SALES_RECEIPT_ID);
                    if ($data) {
                        $JNO =  $this->accountJournalServices->getRecord(
                            $this->salesReceiptServices->object_type_sales_receipt,
                            $this->SALES_RECEIPT_ID
                        );
                        $this->accountJournalServices->AccountSwitch(
                            $this->lineINCOME_ACCOUNT_ID,
                            $dataItem->INCOME_ACCOUNT_ID,
                            $this->LOCATION_ID,
                            $JNO,
                            $dataItem->ITEM_ID,
                            $Id,
                            $this->salesReceiptServices->object_type_sales_receipt_items,
                            $data->DATE,
                            1
                        );
                    }
                }
            }

            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId ?? 0);

            $this->salesReceiptServices->ItemUpdate(
                $Id,
                $this->SALES_RECEIPT_ID,
                $this->lineItemId,
                $this->lineQty,
                $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                (float)  $unitRelated['QUANTITY'],
                $this->lineRate,
                0,
                $this->lineAmount,
                $this->lineTax,
                $this->lineTaxable,
                $this->lineTaxAmount,
                $this->lineBatchId,
                $this->linePriceLevelId,
                $this->lineINCOME_ACCOUNT_ID
            );


            $getResult = $this->salesReceiptServices->ReComputed($this->SALES_RECEIPT_ID);

            DB::commit();
            $this->dispatch('update-amount', result: $getResult);
            $this->itemList = $this->salesReceiptServices->ItemView($this->SALES_RECEIPT_ID);
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

            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->salesReceiptServices->object_type_sales_receipt,
                    $this->SALES_RECEIPT_ID
                );
                if ($JOURNAL_NO  ==  0) {
                    session()->flash('message', 'journal not found');
                    return;
                }
                $invoiceDate = $this->salesReceiptServices->get($this->SALES_RECEIPT_ID);
                if ($invoiceDate) {
                    $invoiceItemData = $this->salesReceiptServices->ItemGet($Id, $this->SALES_RECEIPT_ID,);
                    if ($invoiceItemData) {

                        // Inventory
                        $this->itemInventoryServices->InventoryModify(
                            $invoiceItemData->ITEM_ID,
                            $invoiceDate->LOCATION_ID,
                            $Id,
                            $this->salesReceiptServices->document_type_id,
                            $invoiceDate->DATE,
                            0,
                            0,
                            0
                        );

                        // INCOME_ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $invoiceItemData->INCOME_ACCOUNT_ID,
                            $invoiceDate->LOCATION_ID,
                            $JOURNAL_NO,
                            $invoiceItemData->ITEM_ID,
                            $Id,
                            $this->salesReceiptServices->object_type_sales_receipt_items,
                            $invoiceDate->DATE,
                            1,

                        );
                        // COGS_ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $invoiceItemData->COGS_ACCOUNT_ID,
                            $invoiceDate->LOCATION_ID,
                            $JOURNAL_NO,
                            $invoiceItemData->ITEM_ID,
                            $Id,
                            $this->salesReceiptServices->object_type_sales_receipt_items,
                            $invoiceDate->DATE,
                            0,

                        );
                        // ASSET_ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $invoiceItemData->ASSET_ACCOUNT_ID,
                            $invoiceDate->LOCATION_ID,
                            $JOURNAL_NO,
                            $invoiceItemData->ITEM_ID,
                            $Id,
                            $this->salesReceiptServices->object_type_sales_receipt_items,
                            $invoiceDate->DATE,
                            1,

                        );
                    }
                }
            }

            $this->salesReceiptServices->ItemDelete($Id, $this->SALES_RECEIPT_ID);
            $getResult = $this->salesReceiptServices->ReComputed($this->SALES_RECEIPT_ID);
            DB::commit();
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
        $this->itemList = $this->salesReceiptServices->ItemView($this->SALES_RECEIPT_ID);
    }
    public function render()
    {
        $this->getReload();
        return view('livewire.sales-receipt.sales-receipt-form-items');
    }
}
