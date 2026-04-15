<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\ItemTreatmentServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\ServiceChargeServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class OtherChargesModal extends Component
{
    public bool $INCOME_ACCOUNT_IS_LOCK = false;
    public int $ITEM_TREATMENT_ID;
    public int $LOCATION_ID;
    public int $ITEM_ID;
    public int $ITEM_TYPE = 0;
    public string $ITEM_NAME;
    public int $HEMO_ID;
    public bool $haveTrigger = false;
    public float $QUANTITY;
    public bool $showModal;
    public int $J_QTY;
    public string $J_ITEM_NAME;
    public $dataList = [];
    public $unitList = [];
    public int $UNIT_ID = 0;
    public bool $IS_JUSTIFY = false;
    public string $JUSTIFY_NOTES;
    private $serviceChargeServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    private $locationServices;
    private $priceLevelLineServices;
    private $itemServices;
    private $hemoServices;
    private $itemInventoryServices;
    public function boot(
        ServiceChargeServices $serviceChargeServices,
        ItemTreatmentServices $itemTreatmentServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        LocationServices $locationServices,
        PriceLevelLineServices  $priceLevelLineServices,
        ItemServices $itemServices,
        HemoServices $hemoServices,
        ItemInventoryServices  $itemInventoryServices
    ) {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->locationServices = $locationServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->itemServices = $itemServices;
        $this->hemoServices = $hemoServices;
        $this->itemInventoryServices = $itemInventoryServices;
    }
    #[On('adding-item')]
    public function openModal($result)
    {
        $this->dataList = [];
        $this->unitList = [];
        $this->QUANTITY = 0;
        $this->UNIT_ID = 0;
        $this->HEMO_ID = $result['HEMO_ID'];
        $this->ITEM_ID = $result['ITEM_ID'] ?? 0;
        $this->ITEM_NAME = $result['ITEM_NAME'] ?? '';
        $this->haveTrigger = false;
        $this->IS_JUSTIFY = false;
        $this->JUSTIFY_NOTES = '';
        $this->J_QTY = 0;
        $this->J_ITEM_NAME = '';

        $dataHemo =  $this->hemoServices->Get($this->HEMO_ID);
        $dataItem = $this->itemServices->get($this->ITEM_ID);

        if ($dataHemo && $dataItem) {
            $this->LOCATION_ID  = $dataHemo->LOCATION_ID;
            $this->ITEM_TYPE = $dataItem->TYPE ?? 0;
            $this->ITEM_TREATMENT_ID =  $this->itemTreatmentServices->getItemTreatmentID(
                $this->ITEM_ID,
                $this->LOCATION_ID,
                $dataItem->BASE_UNIT_ID ?? 0
            );

            $this->dataList = $this->itemTreatmentServices->listItemTrigger($this->ITEM_TREATMENT_ID);


            foreach ($this->dataList as $list) {
                $this->J_QTY = $list->QUANTITY;
                $this->J_ITEM_NAME = $list->ITEM_NAME;
                $this->haveTrigger = true;
            }
        }
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function AddCharge()
    {

        $this->validate(
            [
                'QUANTITY' => 'required|int|min:1',
                'JUSTIFY_NOTES' => $this->IS_JUSTIFY ? 'required|string|min:4' : 'nullable',
                'UNIT_ID'       => $this->ITEM_TYPE < 2 ? 'required|numeric|exists:unit_of_measure,id' : 'nullable'
            ],
            [],
            [
                'QUANTITY'  => 'Quantity',
                'JUSTIFY_NOTES' => 'Justification Notes',
                'UNIT_ID'       => 'Unit of measure'
            ]
        );

        $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID ?? 0);
        $data = $this->itemServices->get($this->ITEM_ID);
        if ($data) {
            $QTY_BASED = (float) $unitRelated['QUANTITY'] ?? 1;
            $PRICE_LEVEL_ID  = 0;
            $UNIT_ID  = $this->UNIT_ID; //$data->BASE_UNIT_ID ?? 0;
            $RATE = $data->RATE ?? 0;
            $TAX = $data->TAXABLE ?? 0;
            $SK_LINE_ID = null;
            $hemoData = $this->hemoServices->Get($this->HEMO_ID);

            if ($data->TYPE < 2) {
                $onHandQty  = $this->itemServices->getOnhand($data->ID, $hemoData->LOCATION_ID);
                if ($onHandQty <= 0) {
                   // session()->flash('error', 'Invalid add charges: The item is out of stock.');
                   // return;
                }
            }

            DB::beginTransaction();
            try {
                $scData =  $this->serviceChargeServices->ServicesChargesGetFirst(
                    $hemoData->DATE,
                    $hemoData->CUSTOMER_ID,
                    $hemoData->LOCATION_ID
                );
                if ($scData) {
                    $dataLoc = $this->locationServices->get($hemoData->LOCATION_ID);
                    if ($dataLoc) {
                        if ($dataLoc->PRICE_LEVEL_ID > 0) {
                            $PRICE_LEVEL_ID = $dataLoc->PRICE_LEVEL_ID ?? 0;
                            if ($PRICE_LEVEL_ID > 0) {
                                $RATE = $this->priceLevelLineServices->GetPriceByLocation($hemoData->LOCATION_ID, $this->ITEM_ID);
                            }
                        }
                    }

                    $SC_ITEM_ID =   $this->serviceChargeServices->ItemStore(
                        $scData->ID,
                        $this->ITEM_ID,
                        $this->QUANTITY,
                        $UNIT_ID,
                        $QTY_BASED > 0 ? $QTY_BASED : 1,
                        $RATE,
                        0,
                        $this->QUANTITY * $RATE,
                        $TAX,
                        0,
                        0,
                        $data->COGS_ACCOUNT_ID ?? 0,
                        $data->ASSET_ACCOUNT_ID ?? 0,
                        0,
                        0,
                        false,
                        $PRICE_LEVEL_ID
                    );

                    $SK_LINE_ID =  $this->hemoServices->ItemStore(
                        $this->HEMO_ID,
                        $this->ITEM_ID,
                        $this->QUANTITY,
                        $UNIT_ID,
                        $QTY_BASED > 0 ? $QTY_BASED : 1,
                        true,
                        false,
                        true,
                        $SC_ITEM_ID
                    );
                    $this->serviceChargeServices->ReComputed($scData->ID); // recompute balance
                } else {
                    $SK_LINE_ID = $this->hemoServices->ItemStore(
                        $this->HEMO_ID,
                        $this->ITEM_ID,
                        $this->QUANTITY,
                        $UNIT_ID,
                        $QTY_BASED,
                        true,
                        false,
                        true
                    );
                }


                if ($this->IS_JUSTIFY) {

                    $dataTrigger = $this->itemTreatmentServices->getItemTrigger($this->ITEM_ID, $hemoData->LOCATION_ID, $UNIT_ID);
                    foreach ($dataTrigger  as $list) {
                        $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                        $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                        $R_QTY = $list->QUANTITY + 1 * $this->QUANTITY;
                        $this->hemoServices->ItemStore(
                            $this->HEMO_ID,
                            $list->ITEM_ID,
                            $R_QTY,
                            $list->UNIT_ID ?? 0,
                            $TR_UNIT_BASE_QUANTITY,
                            true,
                            true,
                            false,
                            null,
                            $SK_LINE_ID,
                            true,
                            $this->JUSTIFY_NOTES
                        );
                    }
                } else {
                    $dataTrigger = $this->itemTreatmentServices->getItemTrigger(
                        $this->ITEM_ID,
                        $hemoData->LOCATION_ID,
                        $UNIT_ID
                    );
                    foreach ($dataTrigger  as $list) {
                        $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                        $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                        $R_QTY = $list->QUANTITY * $this->QUANTITY;
                        $this->hemoServices->ItemStore(
                            $this->HEMO_ID,
                            $list->ITEM_ID,
                            $R_QTY,
                            $list->UNIT_ID ?? 0,
                            $TR_UNIT_BASE_QUANTITY,
                            true,
                            true,
                            false,
                            null,
                            $SK_LINE_ID
                        );
                    }
                }


                DB::commit();
                $this->closeModal();
            } catch (\Throwable $th) {
                DB::rollBack();
                session()->flash('error', $th->getMessage());
            
                return;
            }

            $this->dispatch('refresh-item-treatment');
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {

        if (isset($this->ITEM_ID) && $this->ITEM_ID > 0) {
            $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);

        }
        return view('livewire.hemodialysis.other-charges-modal');
    }
}
