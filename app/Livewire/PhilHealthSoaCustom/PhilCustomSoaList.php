<?php

namespace App\Livewire\PhilHealthSoaCustom;

use App\Services\PhilHealthSoaCustomServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Location  | Custom Soa')]
class PhilCustomSoaList extends Component
{
    public $dataList = [];
    public $search;
    private $philHealthSoaCustomServices;
    public function boot(PhilHealthSoaCustomServices $philHealthSoaCustomServices)
    {
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
    }
    public int $LOCATION_ID;
    public function mount($id)
    {
        $this->LOCATION_ID = $id;
    }
    public function delete($id)
    {
        $this->philHealthSoaCustomServices->Delete($id);
    }
    public function render()
    {
        $this->dataList = $this->philHealthSoaCustomServices->List($this->search, $this->LOCATION_ID);

        return view('livewire.phil-health-soa-custom.phil-custom-soa-list');
    }
}
