<?php

namespace App\Livewire\BillCredit;

use App\Services\BillCreditServices;
use App\Services\ComputeServices;
use App\Services\ItemServices;
use App\Services\TaxServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillCreditFormItems extends Component
{
    #[Reactive()]
    public int $BILL_CREDIT_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $TAX_ID;
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
    private $billCreditServices;
    private $computeServices;
    private $unitOfMeasureServices;
    private $taxServices;
    private $itemServices;
    public function boot(
        BillCreditServices $billCreditServices,
        ComputeServices $computeServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        TaxServices $taxServices,
        ItemServices $itemServices,
    ) {
        $this->billCreditServices = $billCreditServices;
        $this->computeServices = $computeServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->taxServices = $taxServices;
        $this->itemServices = $itemServices;
    }
    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getByVendor(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getByVendor(false);
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
        $this->BATCH_ID = 0;
        $this->ACCOUNT_ID = false;
        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {
                $this->RATE = $item->COST ?? 0;
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->PURCHASE_DESCRIPTION;
                $this->TAXABLE = $item->TAXABLE;
                $this->BASE_UNIT_ID = $item->BASE_UNIT_ID ? $item->BASE_UNIT_ID : 1;
                $this->ACCOUNT_ID = $item->ASSET_ACCOUNT_ID;
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
                'ITEM_ID' => 'required|not_in:0',
                'QUANTITY' => 'required|not_in:0',
                'RATE' => 'required'
            ],
            [],
            [
                'ITEM_ID' => 'Item',
                'QUANTITY' => 'Quantity',
                'RATE' => 'Cost'
            ]
        );

        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->AMOUNT, $this->TAXABLE, $this->TAX_ID, $taxRate);

            if ($tax_result) {
                $this->TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $this->TAX_AMOUNT = $tax_result['TAX_AMOUNT'];
            }

            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID ?? 0);
            $this->billCreditServices->ItemStore(
                $this->BILL_CREDIT_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_ID > 0 ? $this->UNIT_ID : 0,
                (float)  $unitRelated['QUANTITY'],
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

            $getResult = $this->billCreditServices->ReComputed($this->BILL_CREDIT_ID);
            $this->dispatch('update-amount', result: $getResult);
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
            $this->updatedcodeBase();
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
                'lineQty' => 'required|not_in:0',
            ],
            [],
            [
                'lineQty' => 'Quantity',
            ]
        );


        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->lineAmount, $this->lineTax, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->lineTaxable = $tax_result['TAXABLE_AMOUNT'];
                $this->lineTaxAmount = $tax_result['TAX_AMOUNT'];
            }
            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId ?? 0);
            $this->billCreditServices->ItemUpdate(
                $Id,
                $this->BILL_CREDIT_ID,
                $this->lineItemId,
                $this->lineQty,
                $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                (float)  $unitRelated['QUANTITY'],
                $this->lineRate,
                $this->lineAmount,
                $this->lineTax,
                $this->lineTaxable,
                $this->lineTaxAmount
            );

            $getResult = $this->billCreditServices->ReComputed($this->BILL_CREDIT_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->itemList = $this->billCreditServices->ItemView($this->BILL_CREDIT_ID);
            $this->editItemId = null;
            $this->lineQty = 0;
            $this->lineUnitId = 0;
            $this->lineRate = 0;
            $this->lineAmount = 0;
            $this->lineTax = false;
            $this->lineItemId = 0;
        } catch (\Exception $e) {

            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }

    public function deleteItem($Id)
    {
        try {
            $this->billCreditServices->ItemDelete(
                $Id,
                $this->BILL_CREDIT_ID
            );

            $getResult = $this->billCreditServices->ReComputed($this->BILL_CREDIT_ID);
            $this->dispatch('update-amount', result: $getResult);
        } catch (\Exception $e) {
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
        $this->itemList = $this->billCreditServices->ItemView($this->BILL_CREDIT_ID);
    }
    public function render()
    {
        $this->getReload();
        return view('livewire.bill-credit.bill-credit-form-items');
    }
}
