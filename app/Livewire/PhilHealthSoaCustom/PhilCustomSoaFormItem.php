<?php

namespace App\Livewire\PhilHealthSoaCustom;

use App\Services\ItemServices;
use App\Services\PhilHealthSoaCustomServices;
use Livewire\Component;

class PhilCustomSoaFormItem extends Component
{
    public $SOA_CUSTOM_ID;
    public $ITEM_ID;
    public $dataList = [];
    public $itemList = [];
    private $philHealthSoaCustomServices;
    private $itemServices;
    public function boot(PhilHealthSoaCustomServices $philHealthSoaCustomServices, ItemServices $itemServices)
    {
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
        $this->itemServices = $itemServices;
    }

    public function mount($id)
    {
        $this->ITEM_ID = 0;
        $this->itemList = $this->itemServices->getByCustomer(false);
        $this->SOA_CUSTOM_ID = $id;
    }
    public function save()
    {

        $this->validate(
            ['ITEM_ID' => 'required|not_in:0|exists:item,id'],
            [],
            ['ITEM_ID' => 'Item']
        );

        $IsExist = (bool)  $this->philHealthSoaCustomServices->ItemExist($this->SOA_CUSTOM_ID, $this->ITEM_ID);

        if ($IsExist) {
            session()->flash('error', 'Item already added');
            return;
        }

        $this->philHealthSoaCustomServices->ItemStore($this->SOA_CUSTOM_ID, $this->ITEM_ID);
    }
    public function delete($id)
    {
        $this->philHealthSoaCustomServices->DeleteStore($id);
    }
    public function render()
    {
        $this->dataList = $this->philHealthSoaCustomServices->GetList($this->SOA_CUSTOM_ID);

        return view('livewire.phil-health-soa-custom.phil-custom-soa-form-item');
    }
}
