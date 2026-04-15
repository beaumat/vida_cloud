<?php

namespace App\Livewire\ItemPage;

use App\Services\ItemLocationUnitServices;
use App\Services\LocationServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ItemUnitLocationDefault extends Component
{
    public int $itemId = 0;
    public int $LOCATION_ID;
    public int $PURCHASES_UNIT_ID;
    public int $SALES_UNIT_ID;
    public int $SHIPPING_UNIT_ID;

    public bool $saveSuccess = false;
    public $locationList = [];
    public $unitList = [];
    public $unitLocationList = [];
    public $editItemId = null;

    public int $newPURCHASES_UNIT_ID;
    public int $newSALES_UNIT_ID;
    public int $newSHIPPING_UNIT_ID;

    private $itemLocationUnitServices;
    private $locationServices;
    private $unitOfMeasureServices;
    public function boot(ItemLocationUnitServices $itemLocationUnitServices, LocationServices $locationServices, UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->itemLocationUnitServices = $itemLocationUnitServices;
        $this->locationServices = $locationServices;
        $this->unitOfMeasureServices  = $unitOfMeasureServices;
    }
    public function dropDownload()
    {
        $this->locationList = $this->locationServices->getList();
        $this->unitList =  $this->unitOfMeasureServices->getList();
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->dropDownload();
    }

    public function saveItem()
    {
        $this->validate(
            [
                'LOCATION_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('item_location_units', 'location_id')->where(function ($query) {
                        return $query->where('location_id', $this->LOCATION_ID)->where('item_id', $this->itemId);
                    }),
                ],
                'PURCHASES_UNIT_ID' => 'required|not_in:0',
                'SALES_UNIT_ID' => 'required|not_in:0',
                'SHIPPING_UNIT_ID' => 'required|not_in:0',
            ],
            [],
            [
                'LOCATION_ID' => 'Location',
                'PURCHASES_UNIT_ID' => 'Purchases Unit',
                'SALES_UNIT_ID' => 'Sales Unit',
                'SHIPPING_UNIT_ID' => 'Shipping Unit'
            ]
        );
        try {
            $this->itemLocationUnitServices->Store($this->itemId, $this->LOCATION_ID, $this->PURCHASES_UNIT_ID, $this->SALES_UNIT_ID, $this->SHIPPING_UNIT_ID);
            $this->dropDownload();
            $this->LOCATION_ID = 0;
            $this->PURCHASES_UNIT_ID = 0;
            $this->SALES_UNIT_ID = 0;
            $this->SHIPPING_UNIT_ID = 0;

            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->unitLocationList = $this->itemLocationUnitServices->Search($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function editItem(int $id, int $po_id, int $sale_id, int $ship_id): void
    {
        $this->newPURCHASES_UNIT_ID = $po_id;
        $this->newSALES_UNIT_ID = $sale_id;
        $this->newSHIPPING_UNIT_ID = $ship_id;
        $this->editItemId = $id;
    }
    public function updateItem($id): void
    {

        $this->validate(
            [
                'newPURCHASES_UNIT_ID' => 'required|not_in:0',
                'newSALES_UNIT_ID' => 'required|not_in:0',
                'newSHIPPING_UNIT_ID' => 'required|not_in:0',
            ],
            [],
            [
                'newPURCHASES_UNIT_ID' => 'Purchases Unit',
                'newSALES_UNIT_ID' => 'Sales Unit',
                'newSHIPPING_UNIT_ID' => 'Shipping Unit'
            ]
        );

        try {
            $this->itemLocationUnitServices->Update($id, $this->newPURCHASES_UNIT_ID, $this->newSALES_UNIT_ID, $this->newSHIPPING_UNIT_ID);
            $this->editItemId = null;
            $this->unitLocationList = $this->itemLocationUnitServices->Search($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem(): void
    {
        $this->editItemId = null;
    }
    public function deleteItem(int $ID): void
    {
        try {
            $this->itemLocationUnitServices->Delete($ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $this->unitLocationList = $this->itemLocationUnitServices->Search($this->itemId);
        return view('livewire.item-page.item-unit-location-default');
    }
}
