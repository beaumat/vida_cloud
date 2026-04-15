<?php

namespace App\Livewire\ItemGroupPage;

use App\Models\ItemGroup;
use App\Models\ItemType;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use App\Services\ItemGroupServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Item Group - Form')]
class ItemGroupForm extends Component
{

    public $itemType = [];

    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public int $ITEM_TYPE;

    public function mount($id = null)
    {
        $this->itemType = ItemType::all();

        if (is_numeric($id)) {

            $itemGroup = ItemGroup::where('ID', $id)->first();

            if ($itemGroup) {
                $this->ID = $itemGroup->ID;
                $this->CODE = $itemGroup->CODE;
                $this->DESCRIPTION = $itemGroup->DESCRIPTION;
                $this->ITEM_TYPE = $itemGroup->ITEM_TYPE;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryitem_group')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
        $this->ITEM_TYPE = 0;
    }
    public function save(ItemGroupServices $itemGroupServices)
    {
        $this->validate(
            [
                'CODE' => 'required|max:10|unique:item_group,code,' . $this->ID,
                'DESCRIPTION' => 'required|max:100|unique:item_group,description,' . $this->ID,
                'ITEM_TYPE' => 'required',
            ],
            [],
            [
                'CODE' => 'Code',
                'DESCRIPTION' => 'Description',
                'ITEM_TYPE' => 'Item Type',
            ]
        );


        try {
            
            if ($this->ID === 0) {
               $this->ID = $itemGroupServices->Store($this->CODE, $this->DESCRIPTION, $this->ITEM_TYPE);
                session()->flash('message', 'Successfully created.');
            } else {
                $itemGroupServices->Update($this->ID, $this->CODE, $this->DESCRIPTION, $this->ITEM_TYPE);
                session()->flash('message', 'Successfully updated.');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $$errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.item-group.item-group-form');
    }
    

}
