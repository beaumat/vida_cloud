<?php

namespace App\Livewire\List;

use App\Exports\InventoryListItemExport;
use App\Services\DateServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Item Inventory')]
class ItemActiveList extends Component
{

  public bool $isControl = false;

  public $search = '';
  private $userServices;
  private $dateServices;
  private $locationServices;
  public int $LOCATION_ID;
  public int $PRICE_LEVEL_ID;
  public $locationList = [];
  public $DATE;
  private $itemServices;
  public bool $isDesc = false;
  public string $sortby;
  public $dataList = [];
  public $showOutofStock = false;
  private $priceLevelLineServices;

  public function OnClick(int $ID)
  {
    $data = [
      'ITEM_ID' => $ID,
      'LOCATION_ID' => $this->LOCATION_ID,
      'showModal' => true,
      'DATE' => $this->DATE
    ];

    $this->dispatch('open-modal', result: $data);
  }
  public function showNotInclude()
  {
    $data = [
      'PRICE_LEVEL_ID' => $this->PRICE_LEVEL_ID,
      'LOCATION_ID' => $this->LOCATION_ID
    ];
    $this->dispatch('not-include-show', result: $data);
  }
  public function boot(UserServices $userServices, DateServices $dateServices, LocationServices $locationServices, ItemServices $itemServices, PriceLevelLineServices $priceLevelLineServices)
  {
    $this->userServices = $userServices;
    $this->dateServices = $dateServices;
    $this->locationServices = $locationServices;
    $this->itemServices = $itemServices;
    $this->priceLevelLineServices = $priceLevelLineServices;
  }
  public function mount()
  {
    $this->sortby = 'c.DESCRIPTION';
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
    $this->DATE = $this->dateServices->NowDate();
    $this->locationList = $this->locationServices->getList();
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
    $this->getPriceLevel();
    $this->refreshItem();
  }
  public function updatedshowOutofStock()
  {
    $this->dataList = $this->itemServices->getActiveItems(
      $this->search,
      $this->LOCATION_ID,
      $this->sortby,
      $this->isDesc,
      $this->showOutofStock,
      $this->DATE
    );
  }
  public function getPriceLevel()
  {
    $data = $this->locationServices->get($this->LOCATION_ID);
    if ($data) {
      $this->PRICE_LEVEL_ID = $data->PRICE_LEVEL_ID;
    }
  }
  public function sorting(string $column)
  {
    if ($this->sortby == $column) {
      $this->isDesc = $this->isDesc ? false : true;
      $this->refreshItem();
      return;
    }
    $this->isDesc = $this->isDesc ? false : true;
    $this->sortby = $column;
    $this->refreshItem();
  }
  public function updatedsearch()
  {
    $this->refreshItem();
  }
  public function updatedDate()
  {
    $this->refreshItem();
  }
  public function updatedLocationId()
  {

    try {
      $this->userServices->SwapLocation($this->LOCATION_ID);
      $this->getPriceLevel();
      $this->refreshItem();
    } catch (\Exception $e) {
      $errorMessage = 'Error occurred: ' . $e->getMessage();
      session()->flash('error', $errorMessage);
    }

  }
  #[On('refresh-active-list')]
  public function onset()
  {
    $this->refreshItem();
  }
  private function refreshItem()
  {
    $this->dataList = $this->itemServices->getActiveItems(
      $this->search,
      $this->LOCATION_ID,
      $this->sortby,
      $this->isDesc,
      $this->showOutofStock,
      $this->DATE
    );
  }
  public function exportData()
  {
    $newData = $this->itemServices->getActiveItems(
      $this->search,
      $this->LOCATION_ID,
      $this->sortby,
      $this->isDesc,
      $this->showOutofStock,
      $this->DATE
    );
    return Excel::download(new InventoryListItemExport(
      $newData
    ), "Item-Inventory.xlsx");
  }

  public function itemNotInclude(int $ITEM_ID)
  {
    if ($this->PRICE_LEVEL_ID > 0) {
      $this->priceLevelLineServices->Remove($ITEM_ID, $this->PRICE_LEVEL_ID);
      $this->refreshItem();
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

    return view('livewire.list.item-active-list');
  }
}
