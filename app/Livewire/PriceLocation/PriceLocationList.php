<?php

namespace App\Livewire\PriceLocation;

use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Price List By Location')]
class PriceLocationList extends Component
{


    public bool $isControl = false;
    public int $PRICE_LEVEL_ID = 0;
    public bool $showCost  = false;
    public $editId = null;
    public $editPrice = 0;
    public $editCost = 0;
    public $search = '';
    public $dataList = [];
    public $locationList = [];
    public $subList = [];
    public  int $LOCATION_ID;
    public int $SUB_CLASS_ID = 0;
    private $locationServices;
    private $priceLevelLineServices;
    private $itemServices;
    private $userServices;
    private $itemSubClassServices;
    public function boot(
        ItemServices $itemServices,
        UserServices $userServices,
        LocationServices $locationServices,
        PriceLevelLineServices $priceLevelLineServices,
        ItemSubClassServices $itemSubClassServices
    ) {
        $this->locationServices = $locationServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->userServices = $userServices;
        $this->itemServices = $itemServices;
        $this->itemSubClassServices = $itemSubClassServices;
    }
    public function showNotInclude()
    {
        $data =  [
            'PRICE_LEVEL_ID' => $this->PRICE_LEVEL_ID,
            'LOCATION_ID'   => $this->LOCATION_ID,
            'SUB_CLASS_ID'  => $this->SUB_CLASS_ID
        ];
        $this->dispatch('not-include-show', result: $data);
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->getPriceLevel();

        $this->subList = $this->itemSubClassServices->getList();
    }
    public function UpdatedlocationId()
    {

        $this->getPriceLevel();
    }
    private function getPriceLevel()
    {
        $data = $this->locationServices->get($this->LOCATION_ID);
        if ($data) {
            $this->PRICE_LEVEL_ID = $data->PRICE_LEVEL_ID ?? 0;
        }
    }
    public function edit($id)
    {
        $data =  $this->priceLevelLineServices->PriceExists(
            $id,
            $this->LOCATION_ID
        );
        if ($data) {
            $this->editId = $id;
            $this->editPrice = number_format($data['PRICE'] ?? 0, 2, ".", "");
            $this->editCost = number_format($data['COST'] ?? 0, 2, ".", "");
        }
    }
    public function cancel()
    {
        $this->editId = null;
        $this->editPrice  = 0;
    }
    public function autoUpdate()
    {


        $locData =  $this->locationServices->get($this->LOCATION_ID);
        if ($locData) {
            if ($locData->PRICE_LEVEL_ID > 0) {
                $data = $this->itemServices->SearchPriceLocation("", 600);

                foreach ($data as $list) {

                    $this->UpdateItem(
                        $locData->PRICE_LEVEL_ID,
                        $list->ID,
                        $this->LOCATION_ID,
                        $list->RATE ?? 0,
                        $list->COST ?? 0
                    );
                }
            }
        }
    }
    public function itemNotInclude(int $ITEM_ID)
    {
        $this->priceLevelLineServices->Remove($ITEM_ID, $this->PRICE_LEVEL_ID);
    }
    public function save()
    {
        $locData =  $this->locationServices->get($this->LOCATION_ID);
        if ($locData) {
            if ($locData->PRICE_LEVEL_ID > 0) {
                $this->UpdateItem(
                    $locData->PRICE_LEVEL_ID,
                    $this->editId,
                    $this->LOCATION_ID,
                    (float)$this->editPrice,
                    (float)$this->editCost
                );
                DB::commit();
                $this->cancel();
                return;
            }
            session()->flash('error', 'price level not modify');
        }
    }

    private function UpdateItem(int $PRICE_LEVEL_ID, int $ITEM_ID, int $LOCATION_ID, float $PRICE, float $COST)
    {
        DB::beginTransaction();
        try {
            $isExistsID =  $this->priceLevelLineServices->DataExists($ITEM_ID, $LOCATION_ID);
            if ($isExistsID > 0) {

                $this->priceLevelLineServices->Update(
                    $isExistsID,
                    $PRICE,
                    $COST
                );
            } else {

                $this->priceLevelLineServices->Store(
                    $PRICE_LEVEL_ID,
                    $ITEM_ID,
                    $PRICE,
                    $COST
                );
            }
            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();
            dd($e->getMessage());
            // $errorMessage = 'Error occurred: ' . $e->getMessage();
            // session()->flash('error', $errorMessage);
            //throw $th;
        }
    }

    #[On('refresh-active-list')]
    public function render()
    {
        $this->dataList = $this->itemServices->PriceLevelItemList(
            $this->search,
            $this->SUB_CLASS_ID,
            $this->LOCATION_ID
        );

        return view('livewire.price-location.price-location-list');
    }
}
