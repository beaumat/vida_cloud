<?php

namespace App\Livewire\InventoryReport;

use App\Models\ItemInventory;
use App\Services\DateServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Inventory Validation Report')]
class ValidationSummaryReport extends Component
{
    public $dataList = [];
    public $DATE;
    public int $LOCATION_ID;
    public $locationList = [];
    private $itemInventoryServices;
    private $locationServices;
    private $userServices;
    private $dateServices;

    public function boot(ItemInventoryServices $itemInventoryServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->itemInventoryServices = $itemInventoryServices;
        $this->userServices = $userServices;
        $this->locationServices = $locationServices;
        $this->dateServices = $dateServices;
    }
    public function generate()
    {
        $this->dataList =  $this->itemInventoryServices->ValidationSummaryReport($this->DATE, $this->LOCATION_ID);
    }
    public function mount()
    {
        $this->DATE = $this->dateServices->NowDate();
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
    }
    public function render()
    {
        return view('livewire.inventory-report.validation-summary-report');
    }
}
