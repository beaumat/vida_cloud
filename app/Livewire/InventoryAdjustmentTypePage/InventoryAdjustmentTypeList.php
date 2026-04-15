<?php

namespace App\Livewire\InventoryAdjustmentTypePage;

use App\Services\InventoryAdjustmentTypeServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Inventory Adjustment Type List')]
class InventoryAdjustmentTypeList extends Component
{
    public $inventoryAdjustmentType = [];
    public $search = '';
    public function updatedsearch(InventoryAdjustmentTypeServices $inventoryAdjustmentTypeServices)
    {
        $this->inventoryAdjustmentType = $inventoryAdjustmentTypeServices->Search($this->search);
    }
    public function delete($id, InventoryAdjustmentTypeServices $inventoryAdjustmentTypeServices)
    {
        try {
            $inventoryAdjustmentTypeServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->inventoryAdjustmentType = $inventoryAdjustmentTypeServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(InventoryAdjustmentTypeServices $inventoryAdjustmentTypeServices)
    {
        $this->inventoryAdjustmentType = $inventoryAdjustmentTypeServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.inventory-adjustment-type.inventory-adjustment-type-list');
    }
}
