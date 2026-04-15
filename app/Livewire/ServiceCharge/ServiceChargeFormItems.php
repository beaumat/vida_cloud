<?php
namespace App\Livewire\ServiceCharge;

use App\Services\ComputeServices;
use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\ItemAccountServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use App\Services\LocationServices;
use App\Services\PhilhealthItemAdjustmentServices;
use App\Services\PhilHealthServices;
use App\Services\PriceLevelLineServices;
use App\Services\ServiceChargeServices;
use App\Services\TaxServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ServiceChargeFormItems extends Component
{

    #[Reactive]
    public int $SERVICE_CHARGES_ID;

    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $TAX_ID;

    #[Reactive]
    public $LOCATION_ID;

    #[Reactive]
    public $PATIENT_ID;

    #[Reactive]
    public bool $WALK_IN;

    #[Reactive]
    public bool $HEMO_ID;

    public bool $isAdmin = false;
    public bool $alowedEdit = false;
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
    public bool $TAXABLE;
    public float $TAXABLE_AMOUNT;
    public float $TAX_AMOUNT;
    public int $COGS_ACCOUNT_ID;
    public int $ASSET_ACCOUNT_ID;
    public int $INCOME_ACCOUNT_ID;
    public int $REF_LINE_ID;
    public int $BATCH_ID;
    public int $GROUP_LINE_ID;
    public bool $PRINT_IN_FORMS;
    public int $PRICE_LEVEL_ID;
    public $itemList = [];
    public $editItemId = null;
    public bool $canBeQtyEdit = false;
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
    public $accountList = [];
    public $editAccountList = [];
    public int $lineINCOME_ACCOUNT_ID = 0;
    public int $lineItemId = 0;
    private $serviceChargeServices;
    private $computeServices;
    private $unitOfMeasureServices;
    private $taxServices;
    private $itemServices;
    private $itemSubClassServices;
    public $CLASS_DESCRIPTION;
    public bool $editPrice = true;
    public string $DATE_NOW;
    private $hemoServices;
    private $dateServices;
    private $philHealthServices;
    private $locationServices;
    private $priceLevelLineServices;
    private $itemAccountServices;
    private $philhealthItemAdjustmentServices;
    private $itemInventoryServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        ServiceChargeServices $serviceChargeServices,
        ComputeServices $computeServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        TaxServices $taxServices,
        ItemServices $itemServices,
        ItemSubClassServices $itemSubClassServices,
        HemoServices $hemoServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        PriceLevelLineServices $priceLevelLineServices,
        ItemAccountServices $itemAccountServices,
        PhilhealthItemAdjustmentServices $philhealthItemAdjustmentServices,
        ItemInventoryServices $itemInventoryServices
    ) {
        $this->philHealthServices               = $philHealthServices;
        $this->serviceChargeServices            = $serviceChargeServices;
        $this->computeServices                  = $computeServices;
        $this->unitOfMeasureServices            = $unitOfMeasureServices;
        $this->taxServices                      = $taxServices;
        $this->itemServices                     = $itemServices;
        $this->itemSubClassServices             = $itemSubClassServices;
        $this->hemoServices                     = $hemoServices;
        $this->dateServices                     = $dateServices;
        $this->locationServices                 = $locationServices;
        $this->priceLevelLineServices           = $priceLevelLineServices;
        $this->itemAccountServices              = $itemAccountServices;
        $this->philhealthItemAdjustmentServices = $philhealthItemAdjustmentServices;
        $this->itemInventoryServices            = $itemInventoryServices;
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
                $qty          = $this->QUANTITY > 0 ? $this->QUANTITY : 1;
                $this->AMOUNT = $qty * $this->RATE;
            } else {
                $this->QUANTITY = 1;
                $this->AMOUNT   = 0;
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
    public function openPayment(int $ID, float $AMOUNT)
    {
        $itemdata = [
            'SERVICE_CHARGES_ITEM_ID'     => $ID,
            'SERVICE_CHARGES_ITEM_AMOUNT' => $AMOUNT,
        ];
        $this->getRefershItem($ID);
        $this->dispatch('payment-avaliable-prompt', itemdata: $itemdata);
    }
    public function cashPayment(int $ID, float $AMOUNT)
    {
        $itemdata = [
            'SERVICE_CHARGES_ITEM_ID'     => $ID,
            'SERVICE_CHARGES_ITEM_AMOUNT' => $AMOUNT,
        ];
        $this->getRefershItem($ID);
        $this->dispatch('cash-payment-prompt', itemdata: $itemdata);
    }
    public bool $reloadAccount = false;
    private function AccountLoad()
    {
        $this->accountList = $this->itemAccountServices->AccountList($this->ITEM_ID);

        $this->reloadAccount = $this->reloadAccount ? false : true;
    }
    public function updateditemid()
    {
        $this->accountList       = [];
        $this->INCOME_ACCOUNT_ID = 0;
        $this->UNIT_ID           = 0;
        $this->QUANTITY          = 1;
        $this->RATE              = 0;
        $this->ITEM_CODE         = '';
        $this->ITEM_DESCRIPTION  = '';
        $this->TAXABLE           = false;
        $this->AMOUNT            = 0;
        $this->unitList          = [];
        $this->RATE_TYPE         = 0;
        $this->CLASS_DESCRIPTION = '';
        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {

                if ($this->PRICE_LEVEL_ID > 0) {
                    $this->RATE = (float) $this->priceLevelLineServices->GetPriceByLocation($this->LOCATION_ID, $this->ITEM_ID);
                } else {
                    $this->RATE = (float) $item->RATE;
                }

                $this->AccountLoad();
                $this->ITEM_DESCRIPTION  = $item->DESCRIPTION;
                $this->TAXABLE           = $item->TAXABLE;
                $this->UNIT_ID           = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 1;
                $this->INCOME_ACCOUNT_ID = 0;
                $this->COGS_ACCOUNT_ID   = $item->COGS_ACCOUNT_ID ?? 0;
                $this->ASSET_ACCOUNT_ID  = $item->ASSET_ACCOUNT_ID ?? 0;
                $this->GROUP_LINE_ID     = false;
                $this->PRINT_IN_FORMS    = false;
                $this->getAmount();
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

        $dataLoc = $this->locationServices->get($this->LOCATION_ID);
        if ($dataLoc) {
            if ($dataLoc->PRICE_LEVEL_ID > 0) {
                $this->PRICE_LEVEL_ID = $dataLoc->PRICE_LEVEL_ID ?? 0;
            }
        }

        $this->QUANTITY = 0;
        $this->RATE     = 0;
        $this->AMOUNT   = 0.00;
        $this->updatedcodeBase();
        if (Auth::user()->name == 'admin') {
            $this->isAdmin      = true;
            $this->canBeQtyEdit = true;
        }
    }
    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID'  => 'required|not_in:0',
                'QUANTITY' => 'required|numeric|not_in:0',
                'RATE'     => 'required|numeric',
            ],
            [],
            [
                'ITEM_ID'  => 'Item',
                'QUANTITY' => 'Quantity',
                'RATE'     => 'Rate',
            ]
        );
        DB::beginTransaction();
        try {
            $taxRate    = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->AMOUNT, $this->TAXABLE, $this->TAX_ID, $taxRate);

            if ($tax_result) {
                $this->TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $this->TAX_AMOUNT     = $tax_result['TAX_AMOUNT'];
            }
            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID ?? 0);

            $SK_ID = (int) $this->serviceChargeServices->ItemStore(
                $this->SERVICE_CHARGES_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_ID > 0 ? $this->UNIT_ID : 0,
                (float) ($unitRelated['QUANTITY'] ?? 0),
                $this->RATE,
                $this->RATE_TYPE,
                $this->AMOUNT,
                $this->TAXABLE,
                $this->TAXABLE_AMOUNT,
                $this->TAX_AMOUNT,
                $this->COGS_ACCOUNT_ID,
                $this->ASSET_ACCOUNT_ID,
                $this->INCOME_ACCOUNT_ID,
                $this->GROUP_LINE_ID,
                $this->PRINT_IN_FORMS,
                $this->PRICE_LEVEL_ID
            );

            $dataSC = $this->serviceChargeServices->get($this->SERVICE_CHARGES_ID);
            if ($dataSC) {
                $this->hemoServices->ItemQuery(
                    $dataSC->PATIENT_ID,
                    $dataSC->DATE,
                    $dataSC->LOCATION_ID,
                    $this->ITEM_ID,
                    $this->QUANTITY,
                    false,
                    $this->UNIT_ID > 0 ? $this->UNIT_ID : 0,
                    $SK_ID
                );
            }
            DB::commit();

            $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
            $this->dispatch('update-amount', result: $getResult);

            // Philhealth Purpose
            $prime_item_id           = $this->ITEM_ID;
            $this->ITEM_ID           = 0;
            $this->QUANTITY          = 0;
            $this->UNIT_ID           = 0;
            $this->RATE              = 0;
            $this->RATE_TYPE         = 0;
            $this->AMOUNT            = 0;
            $this->TAXABLE           = false;
            $this->TAXABLE_AMOUNT    = 0;
            $this->TAX_AMOUNT        = 0;
            $this->ITEM_CODE         = '';
            $this->ITEM_DESCRIPTION  = '';
            $this->CLASS_DESCRIPTION = '';
            $this->accountList       = [];
            $this->saveSuccess       = $this->saveSuccess ? false : true;
            $this->updatedcodeBase();

            if ($this->philHealthServices->PHIL_HEALTH_ITEM_ID == $prime_item_id) {
                $count = $this->serviceChargeServices->GetCountByYear(
                    $prime_item_id,
                    $this->dateServices->NowYear(),
                    $this->PATIENT_ID,
                    $this->LOCATION_ID
                );
                $countAdjust = $this->philhealthItemAdjustmentServices->ItemAdjustGet(
                    $this->PATIENT_ID,
                    $this->LOCATION_ID,
                    $this->dateServices->NowYear()
                );

                $totalCount  = $count + $countAdjust;
                $resultcount = ['count' => $totalCount];
                $this->philhealth_Item($resultcount);

            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            $result       = ['message' => $errorMessage, 'type' => 1];
            $this->dispatch('prompt-item-message', result: $result);

        }
        // $this->countRefresh();
    }

    public function philhealth_Item($result)
    {
        $total = (int) $result['count'];
        if ($total <= 156) {
            $message = 'PHIC 156 Treatment. The number of Used is ' . $result['count'];
            $result  = ['message' => $message, 'type' => 0];
            $this->dispatch('prompt-item-message', result: $result);
            return;
        }
        $message = 'PHIC 156 Treatment: The number of uses is ' . $result['count'];
        $result  = ['message' => $message, 'type' => 1];

        $this->dispatch('prompt-item-message', result: $result);

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
                $qty              = $this->lineQty > 0 ? $this->lineQty : 1;
                $this->lineAmount = $qty * $this->lineRate;
            } else {
                $this->lineQty    = 1;
                $this->lineAmount = 0;
            }
        } catch (\Throwable $th) {
        }
    }
    public function editItem(
        int $lineId,
        float $lineQty,
        int $lineUnitId,
        float $lineRate,
        float $lineAmount,
        bool $lineTax,
        int $itemId
    ) {

        $data = $this->serviceChargeServices->getItem($lineId);
        if ($data) {
            $this->editAccountList       = $this->itemAccountServices->AccountList($itemId);
            $this->lineINCOME_ACCOUNT_ID = $data->INCOME_ACCOUNT_ID ?? 0;
            if ($this->hemoServices->IsExist_SC_ITEM($lineId)) {
                $this->canBeQtyEdit = false;
            } else {

                if ($this->DATE_NOW != $data->DATE_LOG) {
                    $this->canBeQtyEdit = false;
                } else {
                    $this->canBeQtyEdit = true;
                }
            }

            if ($this->isAdmin) {
                $this->canBeQtyEdit = true;
            }

            $this->editItemId       = $lineId;
            $this->lineQty          = $lineQty;
            $this->lineUnitId       = $lineUnitId;
            $this->lineRate         = $lineRate;
            $this->lineAmount       = $lineAmount;
            $this->lineTax          = $lineTax;
            $this->lineItemId       = $itemId;
            $this->lineBatchId      = 0;
            $this->linePriceLevelId = 0;
        }
    }
    public function updateItem(int $Id)
    {

        $this->validate(
            [
                'lineQty'  => 'required|numeric|not_in:0',
                'lineRate' => 'required|numeric',
            ],
            [],
            [
                'lineQty'  => 'Quantity',
                'lineRate' => 'Rate',
            ]
        );

        DB::beginTransaction();
        try {
            $taxRate    = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->lineAmount, $this->lineTax, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->lineTaxable   = $tax_result['TAXABLE_AMOUNT'];
                $this->lineTaxAmount = $tax_result['TAX_AMOUNT'];
            }

            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId ?? 0);
            $this->serviceChargeServices->ItemUpdate(
                $Id,
                $this->SERVICE_CHARGES_ID,
                $this->lineItemId,
                $this->lineQty,
                $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                (float) $unitRelated['QUANTITY'],
                $this->lineRate,
                0,
                $this->lineAmount,
                $this->lineTax,
                $this->lineTaxable,
                $this->lineTaxAmount,
                $this->linePriceLevelId,
                $this->lineINCOME_ACCOUNT_ID
            );
            if ($this->canBeQtyEdit) {
                $dataSC = $this->serviceChargeServices->get($this->SERVICE_CHARGES_ID);
                if ($dataSC) {
                    $this->hemoServices->ItemQuery(
                        $dataSC->PATIENT_ID,
                        $dataSC->DATE,
                        $dataSC->LOCATION_ID,
                        $this->lineItemId,
                        $this->lineQty,
                        false,
                        $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                        $Id,
                        $this->canBeQtyEdit
                    );
                }
            }

            $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
            DB::commit();
            $this->dispatch('update-amount', result: $getResult);
            $this->editItemId = null;
            $this->lineQty    = 0;
            $this->lineUnitId = 0;
            $this->lineRate   = 0;
            $this->lineAmount = 0;
            $this->lineTax    = false;
            $this->lineItemId = 0;
            // $this->countRefresh();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            $result       = ['message' => $errorMessage, 'type' => 1];
            $this->dispatch('prompt-item-message', result: $result);
        }
    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }
    private function getRefershItem(int $Id)
    {
        $this->serviceChargeServices->updateServiceChargesItemPaid($Id);
    }
    public function deleteItem(int $Id)
    {
        // checking if homo created
        if ($this->hemoServices->IsExist_SC_ITEM($Id)) {
            session()->flash('error', 'Delete action invalid. Only items from the treatment page can be deleted.');
            return;
        }

        DB::beginTransaction();
        try {
            $getItemInfo = $this->serviceChargeServices->getItemDetails($Id);
            if ($getItemInfo) {
                $this->serviceChargeServices->ItemDelete($Id, $this->SERVICE_CHARGES_ID); // Delete Transaction
                $dataSC = $this->serviceChargeServices->get($this->SERVICE_CHARGES_ID);
                if ($dataSC) {
                    $this->hemoServices->ItemQuery(
                        $dataSC->PATIENT_ID,
                        $dataSC->DATE,
                        $dataSC->LOCATION_ID,
                        $getItemInfo->ITEM_ID,
                        0,
                        true,
                        $getItemInfo->UNIT_ID ?? 0,
                        $Id
                    );

                    $this->itemInventoryServices->RecomputedOnhand(
                        $getItemInfo->ITEM_ID,
                        $this->LOCATION_ID,
                        $dataSC->DATE
                    );

                }

            }
            DB::commit();
            $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
            $this->dispatch('update-amount', result: $getResult);
            // $this->countRefresh();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            $result       = ['message' => $errorMessage, 'type' => 1];
            $this->dispatch('prompt-item-message', result: $result);
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
        $this->DATE_NOW     = $this->dateServices->NowDate();
        $this->editUnitList = $this->unitOfMeasureServices->ItemUnit($this->lineItemId);
        $this->unitList     = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);
        $this->itemList     = $this->serviceChargeServices->ItemView($this->SERVICE_CHARGES_ID);

    }
    public function OpenMultiPayment()
    {
        $this->dispatch('cash-payment-prompt-multi');
    }
    public function render()
    {

        $this->getReload();
        return view('livewire.service-charge.service-charge-form-items');
    }
}
