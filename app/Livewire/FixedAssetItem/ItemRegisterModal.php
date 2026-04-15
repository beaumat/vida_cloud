<?php

namespace App\Livewire\FixedAssetItem;

use App\Services\ItemServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemRegisterModal extends Component
{
    #[Reactive]
    public $LOCATION_ID;
    public $search;
    public  $dataList = [];
    public bool $showModal = false;
    private $itemServices;

    public function boot(ItemServices $itemServices)
    {
        $this->itemServices = $itemServices;
    }
    public function openModal()
    {
        if ($this->LOCATION_ID > 0) {
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function Add(int $ITEM_ID)
    {

        $this->dispatch('open-asset-item', result: ['ID' => 0, 'ITEM_ID' => $ITEM_ID, 'LOCATION_ID' => $this->LOCATION_ID]);
        $this->dispatch('refresh-list');
        
    }
    public function render()
    {
        $this->dataList = $this->itemServices->InventoryItemToFixedAsset($this->search, $this->LOCATION_ID);
        return view('livewire.fixed-asset-item.item-register-modal');
    }
}
