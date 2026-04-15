<?php

namespace App\Livewire\ItemSubClassPage;

use App\Models\ItemClass;
use App\Models\ItemSubClass;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use App\Services\ItemSubClassServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Item Sub-Class - Form')]
class ItemSubClassForm extends Component
{
    public $itemClass = [];
    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public int $CLASS_ID;
    public bool $IN_HEMO;
    public function mount($id = null)
    {
        $this->itemClass = ItemClass::all();

        if (is_numeric($id)) {
            $itemSubClass = ItemSubClass::where('ID', $id)->first();

            if ($itemSubClass) {
                $this->ID = $itemSubClass->ID;
                $this->CODE = $itemSubClass->CODE;
                $this->DESCRIPTION = $itemSubClass->DESCRIPTION;
                $this->CLASS_ID = $itemSubClass->CLASS_ID;
                $this->IN_HEMO = $itemSubClass->IN_HEMO ?? false;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryitem_sub_class')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
        $this->CLASS_ID = 0;
        $this->IN_HEMO = false;
    }


    public function save(ItemSubClassServices $itemSubClassServices)
    {

        if ($this->ID === 0) {
            $this->validate(
                [
                    'DESCRIPTION' => 'required|max:100|unique:item_sub_class,description,' . $this->ID,
                    'CLASS_ID' => 'required|not_in:0',
                ],
                [],
                [
                    'DESCRIPTION' => 'Description',
                    'CLASS_ID' => 'Item Class',
                ]
            );
        } else {
            $this->validate(
                [
                    'CODE' => 'required|max:10|unique:item_sub_class,code,' . $this->ID,
                    'DESCRIPTION' => 'required|max:100|unique:item_sub_class,description,' . $this->ID,
                    'CLASS_ID' => 'required|not_in:0',
                ],
                [],
                [
                    'CODE' => 'Code',
                    'DESCRIPTION' => 'Description',
                    'CLASS_ID' => 'Item Class',
                ]
            );
        }

        try {

            if ($this->ID === 0) {
                $this->ID = $itemSubClassServices->Store($this->CODE, $this->DESCRIPTION, $this->CLASS_ID, $this->IN_HEMO);
                return Redirect::route('maintenanceinventoryitem_sub_class_edit', ['id' => $this->ID])->with('message', 'Successfully created.');
            } else {
                $itemSubClassServices->Update($this->ID, $this->CODE, $this->DESCRIPTION, $this->CLASS_ID,  $this->IN_HEMO);
                session()->flash('message', 'Successfully updated.');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
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
        return view('livewire.item-sub-class.item-sub-class-form');
    }
   




}
