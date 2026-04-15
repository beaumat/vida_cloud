<?php

namespace App\Livewire\List;

use App\Services\ItemServices;
use App\Services\PriceLevelLineServices;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowListNotInclude extends Component
{
    public $search;
    private $itemServices;
    public bool $showModal = false;
    public int $PRICE_LEVEL_ID = 0;
    public int $LOCATION_ID = 0;
    public $itemList = [];
    private $priceLevelLineServices;
    public function boot(PriceLevelLineServices $priceLevelLineServices, ItemServices $itemServices)
    {
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->itemServices = $itemServices;
    }
    public function closeModal()
    {   
       
        $this->showModal = false;
        $this->dispatch('refresh-active-list');
    }
    #[On("not-include-show")]
    public function showingModa($result)
    {
        $this->PRICE_LEVEL_ID = $result['PRICE_LEVEL_ID'];
        $this->LOCATION_ID = $result['LOCATION_ID'];
        $this->showModal = true;
    }
    public function addOn(int $ITEM_ID)
    {
        $this->priceLevelLineServices->Store(
            $this->PRICE_LEVEL_ID,
            $ITEM_ID,
            0,
            0
        );
    }
    public function render()
    {
        if ($this->showModal) {
            $this->itemList = $this->itemServices->getNotIncludeItem($this->search, $this->LOCATION_ID);
        }

        return view('livewire.list.show-list-not-include');
    }
}
