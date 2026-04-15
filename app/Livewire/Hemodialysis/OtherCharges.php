<?php
namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class OtherCharges extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public $hemoData;
    public bool $showModal = false;
    public int $SUB_CLASS_ID;
    public string $ITEM_SUB_NAME;
    private $itemSubClassServices;
    private $itemServices;
    public $qty = [];
    public $itemList = [];
    public $search = '';
    private $hemoServices;

    public function boot(
        ItemSubClassServices $itemSubClassServices,
        ItemServices $itemServices,
        HemoServices $hemoServices,

    ) {
        $this->itemSubClassServices = $itemSubClassServices;
        $this->itemServices         = $itemServices;
        $this->hemoServices         = $hemoServices;
    }
    #[On('open-list-sub-item')]
    public function openModal($result)
    {
        $this->reset('qty');
        $this->SUB_CLASS_ID = $result['SUB_CLASS_ID'];
        $data               = $this->itemSubClassServices->Get($this->SUB_CLASS_ID);
        if ($data) {
            $this->hemoData      = $this->hemoServices->Get($this->HEMO_ID);
            $this->ITEM_SUB_NAME = $data->DESCRIPTION ?? '';
            $this->showModal     = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function Adding(int $ITEM_ID)
    {
        $dataitem = $this->itemServices->get($ITEM_ID);

        $data = [
            'ITEM_ID'   => $dataitem->ID,
            'ITEM_NAME' => $dataitem->DESCRIPTION,
            'HEMO_ID'   => $this->HEMO_ID,
        ];

        $this->dispatch('adding-item', result: $data);
    }

    public function render()
    {
        if ($this->showModal) {
            $this->itemList = $this->itemServices->getItemListBySubId($this->SUB_CLASS_ID, $this->search, $this->HEMO_ID);
        }
        return view('livewire.hemodialysis.other-charges');
    }
}
