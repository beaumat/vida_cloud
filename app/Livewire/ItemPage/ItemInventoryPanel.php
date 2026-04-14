<?php

namespace App\Livewire\ItemPage;

use App\Models\Locations;
use App\Models\StockBin;
use App\Services\ItemPreferenceServices;
use App\Services\LocationServices;
use App\Services\StockBinServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemInventoryPanel extends Component
{
    #[Reactive]
    public int $itemId = 0;
    public int $ID;
    public int $LOCATION_ID = 0;
    public float $ORDER_POINT = 0;
    public float $ORDER_QTY = 0;
    public int $ORDER_LEADTIME = 0;
    public float $ONHAND_MAX_LIMIT = 0;
    public int $STOCK_BIN_ID = 0;

    public float $NEW_ORDER_POINT = 0;
    public float $NEW_ORDER_QTY = 0;
    public int $NEW_ORDER_LEADTIME = 0;
    public float $NEW_ONHAND_MAX_LIMIT = 0;
    public int $NEW_STOCK_BIN_ID = 0;
    public $locationList = [];
    public $stockBinList = [];
    public $saveSuccess = false;
    public $itemPreferenceList = [];
    public $editItemId = null;
    private $itemPreferenceServices;
    private $locationServices;
    private $stockBinServices;
    public function boot(ItemPreferenceServices $itemPreferenceServices, LocationServices $locationServices, StockBinServices $stockBinServices)
    {
        $this->itemPreferenceServices = $itemPreferenceServices;
        $this->locationServices = $locationServices;
        $this->stockBinServices = $stockBinServices;
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->loadDropdown();
    }
    public function loadDropdown()
    {
        $this->locationList =  $this->locationServices->getList();
        $this->stockBinList =  $this->stockBinServices->getList();
    }
    public function saveItem()
    {
        $this->validate(
            [
                'LOCATION_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('item_preference', 'location_id')->where(function ($query) {
                        return $query->where('location_id', $this->LOCATION_ID)->where('item_id', $this->itemId);
                    }),
                ],
                'ORDER_POINT' => 'required|not_in:0',
            ],
            [],
            [
                'LOCATION_ID' => 'Location',
                'ORDER_POINT' => 'Order Point',
            ]
        );

        try {
            $this->temPreferenceServices->Store($this->itemId, $this->LOCATION_ID, $this->ORDER_POINT, $this->ORDER_QTY, $this->ORDER_LEADTIME, $this->ONHAND_MAX_LIMIT, $this->STOCK_BIN_ID);
            $this->LOCATION_ID = 0;
            $this->ORDER_POINT = 0;
            $this->ORDER_QTY = 0;
            $this->ORDER_LEADTIME = 0;
            $this->ONHAND_MAX_LIMIT = 0;
            $this->STOCK_BIN_ID = 0;

            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->loadDropdown();
            $this->itemPreferenceList = $this->itemPreferenceServices->Search($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function editItem($id, $orderPoint, $orderQty, $orderLeadtime, $maxlimit, $bin_id = null)
    {
        $this->NEW_ORDER_POINT = $orderPoint ?  $orderPoint : 0;
        $this->NEW_ORDER_QTY = $orderQty ? $orderQty : 0;
        $this->NEW_ORDER_LEADTIME = $orderLeadtime ? $orderLeadtime : 0;
        $this->NEW_ONHAND_MAX_LIMIT = $maxlimit ? $maxlimit : 0;
        $this->NEW_STOCK_BIN_ID = $bin_id ? $bin_id : 0;
        $this->editItemId = $id;
    }
    public function updateItem($id)
    {
        $this->validate(
            [
                'NEW_ORDER_POINT' => 'required|not_in:0',
            ],
            [],
            [
                'ORDER_POINT' => 'Order Point',
            ]
        );
        try {
            $this->itemPreferenceServices->Update($id, $this->itemId, $this->NEW_ORDER_POINT, $this->NEW_ORDER_QTY, $this->NEW_ORDER_LEADTIME, $this->NEW_ONHAND_MAX_LIMIT, $this->NEW_STOCK_BIN_ID);
            $this->editItemId = null;
            $this->itemPreferenceList = $this->itemPreferenceServices->Search($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }
    public function deleteItem($id): void
    {

        try {
            $this->itemPreferenceServices->Delete($id);
            $this->itemPreferenceList = $this->itemPreferenceServices->Search($this->itemId);
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
        $this->itemPreferenceList = $this->itemPreferenceServices->Search($this->itemId);
        return view('livewire.item-page.item-inventory-panel');
    }
}
