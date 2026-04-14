<?php

namespace App\Livewire\ItemTreatment;

use App\Services\ItemServices;
use App\Services\ItemTreatmentServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemTriggerModal extends Component
{

    #[Reactive]
    public int $ITEM_TREATMENT_ID;
    public int $ITEM_ID;
    public float $QUANTITY;
    public int $UNIT_ID;

    public $itemList = [];
    public $unitList = [];
    public $dataList = [];
    private $itemServices;
    private $unitOfMeasureServices;
    private $itemTreatmentServices;
    public function boot(ItemServices $itemServices, UnitOfMeasureServices $unitOfMeasureServices, ItemTreatmentServices $itemTreatmentServices)
    {
        $this->itemServices = $itemServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
    }
    public $showModal = false;

    public function openModal()
    {
        $this->unitList = [];

        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function saveTrigger()
    {   
        $this->validate([
            'ITEM_ID' => 'required|not_in:0',
            'QUANTITY' => 'required|not_in:0',
            'UNIT_ID' => 'required|not_in:0'
        ], [], [
            'ITEM_ID' => 'Item',
            'QUANTITY' => 'Quantity',
            'UNIT_ID' => 'Unit'
        ]);
        DB::beginTransaction();
        try {
            $this->itemTreatmentServices->storeItemTrigger($this->ITEM_TREATMENT_ID, $this->ITEM_ID, $this->QUANTITY, $this->UNIT_ID);
            DB::commit();
            $this->ITEM_ID = 0;
            $this->QUANTITY = 0;
            $this->UNIT_ID = 0;
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }
    public function delete(int $id)
    {
        try {
            $this->itemTreatmentServices->deleteItemTrigger($id);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
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
        $this->itemList = $this->itemServices->getInventoryItem(false);
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID ?? 0);
        $this->dataList = $this->itemTreatmentServices->listItemTrigger($this->ITEM_TREATMENT_ID ?? 0);
        return view('livewire.item-treatment.item-trigger-modal');
    }
}
