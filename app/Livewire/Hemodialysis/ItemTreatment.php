<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemTreatmentServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemTreatment extends Component
{

    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public $search;
    private $itemTreatmentServices;
    private $hemoServices;
    private $unitOfMeasureServices;
    public function boot(ItemTreatmentServices $itemTreatmentServices, HemoServices $hemoServices, UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->hemoServices = $hemoServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
    }
    public function addItem(int $ItemTreatmentId)
    {
        $data = $this->itemTreatmentServices->Get($ItemTreatmentId);
        if ($data) {
            $gotNew = true;

            try {
                $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($data->ITEM_ID, $data->UNIT_ID ?? 0);
                $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];

                // check if exists
                if ($this->hemoServices->ItemStoreExists($this->HEMO_ID, $data->ITEM_ID, $data->QUANTITY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew, true)) {
                    $this->dispatch('refresh-item-treatment');
                    session()->flash('error', 'Item already exists');
                    return;
                }

                $SK_LINE_ID  =  $this->hemoServices->ItemStore($this->HEMO_ID, $data->ITEM_ID, $data->QUANTITY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew, true);
                $dataTrigger = $this->itemTreatmentServices->listItemTrigger($ItemTreatmentId);
                foreach ($dataTrigger  as $list) {
                    $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                    $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                    $this->hemoServices->ItemStore($this->HEMO_ID, $list->ITEM_ID, $list->QUANTITY, $list->UNIT_ID ?? 0, $TR_UNIT_BASE_QUANTITY, true, true, false, null, $SK_LINE_ID);
                }

                $this->dispatch('refresh-item-treatment');
            } catch (\Throwable $th) {
                session()->flash('error', $th->getMessage());
            }
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
        $dataList = $this->itemTreatmentServices->SearchHemo($this->search, $this->LOCATION_ID, $this->HEMO_ID);
        return view('livewire.hemodialysis.item-treatment', ['dataList' => $dataList]);
    }
}
