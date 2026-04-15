<?php

namespace App\Livewire\ItemTreatment;

use App\Services\ItemTreatmentServices;
use App\Services\LocationServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Item Treatment")]
class ItemTreatmentList extends Component
{

    public $search;
    public $locationId;
    public $locationList = [];
    private $itemTreatmentServices;
    private $locationServices;
    public function boot(ItemTreatmentServices $itemTreatmentServices, LocationServices $locationServices)
    {
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->locationServices = $locationServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationId = 0;
    }
    public function delete(int $id)
    {
        try {
            //code...
            $this->itemTreatmentServices->delete($id);
        } catch (\Exception $e) {
            $this->alertError($e->getMessage());
        }
    }

    public function render()
    {
        $dataList = $this->itemTreatmentServices->Search($this->search, $this->locationId);

        return view('livewire.item-treatment.item-treatment-list', ['dataList' => $dataList]);
    }
}
