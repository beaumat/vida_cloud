<?php

namespace App\Livewire\ItemPage;

use App\Services\ItemAccountServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemAccounts extends Component
{
    #[Reactive]
    public int $ITEM_ID;
    public $searchAvailable;
    public $searchSelected;
    public  $availableList = [];
    public $selectedList  = [];
    private $itemAccountServices;
    public function boot(ItemAccountServices $itemAccountServices)
    {
        $this->itemAccountServices = $itemAccountServices;
    }
    public function mount() {}
    public function Add(int $ACCOUNT_ID)
    {
        $this->itemAccountServices->Store($this->ITEM_ID, $ACCOUNT_ID);
    }
    public function Delete(int $ACCOUNT_ID)
    {
        $this->itemAccountServices->Delete($this->ITEM_ID, $ACCOUNT_ID);
    }
    public function render()
    {

        $this->availableList = $this->itemAccountServices->AccountAvailable($this->searchAvailable, $this->ITEM_ID);
        $this->selectedList = $this->itemAccountServices->AccountSelected($this->searchSelected, $this->ITEM_ID);

        return view('livewire.item-page.item-accounts');
    }
}
