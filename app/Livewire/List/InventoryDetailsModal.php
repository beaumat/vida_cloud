<?php

namespace App\Livewire\List;

use App\Exports\InventoryReportExport;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class InventoryDetailsModal extends Component
{
    public string $DATE;
    public int $ITEM_ID;
    public int $LOCATION_ID;
    public string $ITEM_NAME;
    public $dataList = [];
    public bool $showModal = false;
    private $itemInventoryServices;
    private $itemServices;
    public function boot(ItemInventoryServices $itemInventoryServices, ItemServices $itemServices)
    {
        $this->itemInventoryServices = $itemInventoryServices;
        $this->itemServices = $itemServices;
    }
    public function closeModal()
    {

        $this->showModal = false;
    }
    #[On("open-modal")]
    public function showingModa($result)
    {
        $this->ITEM_ID = $result['ITEM_ID'];
        $data = $this->itemServices->get($this->ITEM_ID);
        if ($data) {
            $this->DATE = $result['DATE'];
            $this->LOCATION_ID = $result['LOCATION_ID'];
            $this->showModal = $result['showModal'];
            $this->ITEM_NAME = $data->DESCRIPTION;
            $this->dispatch('active-scroll');
        }
    }
    public function exportData()
    {
        $dataName = str_replace(' ', '', $this->ITEM_NAME);
        $dataName = str_replace('/', '', $dataName);
        $newData = $this->itemInventoryServices->getDetails($this->ITEM_ID, $this->LOCATION_ID, $this->DATE);
        
        return Excel::download(new InventoryReportExport(
            $newData
        ), "Inventory-Ending-$dataName.xlsx");
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    #[On('active-scroll')]
    public function scrollDown()
    {
        $this->dispatch('scrollToBottom');
    }
    public function refreshOnHand(string $DATE_START)
    {
       
        if ($this->itemInventoryServices->isHaveInventoryAdjustmet($this->ITEM_ID, $this->LOCATION_ID, $DATE_START)) {
            $this->itemInventoryServices->RecomputedOnhand($this->ITEM_ID, $this->LOCATION_ID, $DATE_START);
            session()->flash('message', 'Successfully fixed');
            return;
        }

        session()->flash('error', 'adjustment that date is not found');
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
        if ($this->showModal) {
            $this->dataList = $this->itemInventoryServices->getDetails($this->ITEM_ID, $this->LOCATION_ID, $this->DATE);
        }

        return view('livewire.list.inventory-details-modal');
    }
}
