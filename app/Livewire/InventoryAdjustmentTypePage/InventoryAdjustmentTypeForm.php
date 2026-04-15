<?php

namespace App\Livewire\InventoryAdjustmentTypePage;

use App\Services\InventoryAdjustmentTypeServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use App\Models\Accounts;
use App\Models\InventoryAdjustmentType;
use Livewire\Attributes\Title;

#[Title('Inventory Adjustment Type Form')]
class InventoryAdjustmentTypeForm extends Component
{
    public $accounts = [];
    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public int $ACCOUNT_ID;
    public function mount($id = null)
    {
        $this->accounts = Accounts::where('INACTIVE', '0')->get();
        if (is_numeric($id)) {
            $adjustType = InventoryAdjustmentType::where('ID', $id)->first();
            if ($adjustType) {
                $this->ID = $adjustType->ID;
                $this->CODE = $adjustType->CODE;
                $this->DESCRIPTION = $adjustType->DESCRIPTION;
                $this->ACCOUNT_ID = $adjustType->ACCOUNT_ID;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryinventory_adjustment_type')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
        $this->ACCOUNT_ID = 0;
    }


    public function save(InventoryAdjustmentTypeServices $inventoryAdjustmentTypeServices)
    {
        $this->validate(
            [
                'CODE' => 'required|max:10|unique:inventory_adjustment_type,code,' . $this->ID,
                'DESCRIPTION' => 'required|max:100|unique:inventory_adjustment_type,description,' . $this->ID,
                'ACCOUNT_ID' => 'required|not_in:0',
            ],
            [
                'ACCOUNT_ID.not_in' => 'The Account field is required.',
            ],
            [
                'ACCOUNT_ID' => 'Account',
            ]
        );

        try {
            if ($this->ID === 0) {
                $this->ID = $inventoryAdjustmentTypeServices->Store($this->CODE, $this->DESCRIPTION, $this->ACCOUNT_ID);
                session()->flash('message', 'Successfully created.');
                return;
            }
            $inventoryAdjustmentTypeServices->Update($this->ID, $this->CODE, $this->DESCRIPTION, $this->ACCOUNT_ID);
            session()->flash('message', 'Successfully updated.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.inventory-adjustment-type.inventory-adjustment-type-form');
    }
}
