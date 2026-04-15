<?php

namespace App\Livewire\PriceLevelPage;

use App\Models\ItemGroup;
use App\Models\Items;
use App\Models\PriceLevels;
use App\Models\PriceLevelType;
use App\Services\PriceLevelLineServices;
use App\Services\PriceLevelServices;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Price Level - Form')]
class PriceLevelForm extends Component
{

    public $priceLevelType = [];
    public $itemGroup = [];
    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public int $TYPE;
    public float $RATE;
    public  int $ITEM_GROUP_ID;
    public bool $INACTIVE;
    public int $ITEM_ID;
    public float $CUSTOM_PRICE;
    public $priceLevelLines = [];
    public $itemList = [];
    public $editItemId = null;
    public $newCustomPrice = 0;
    public $search = '';
    private function LoadDropdown()
    {
        $this->itemList = Items::where('INACTIVE', '0')->whereIn('TYPE', ['0', '1', '2', '3'])->get();
        $this->priceLevelType = PriceLevelType::all();
        $this->itemGroup = ItemGroup::all();
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $priceLevel = PriceLevels::where('ID', $id)->first();

            if ($priceLevel) {

                $this->LoadDropdown();
                $this->ID = $priceLevel->ID;
                $this->CODE = $priceLevel->CODE;
                $this->DESCRIPTION = $priceLevel->DESCRIPTION;
                $this->TYPE = $priceLevel->TYPE;
                $this->RATE = floatval($priceLevel->RATE);
                $this->ITEM_GROUP_ID = intval($priceLevel->ITEM_GROUP_ID);
                $this->INACTIVE = $priceLevel->INACTIVE;
                $this->CUSTOM_PRICE = 0;
                $this->ITEM_ID = 0;

                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryprice_level')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
        $this->TYPE = 0;
        $this->RATE = 0;
        $this->ITEM_GROUP_ID = 0;
        $this->INACTIVE = false;
    }


    public function save(PriceLevelServices $priceLevelServices)
    {
        $this->validate(
            [
                'CODE' => 'required|max:10|unique:price_level,code,' . $this->ID,
                'DESCRIPTION' => 'required|max:100|unique:price_level,description,' . $this->ID,
                'TYPE' => 'required',
            ],
            [],
            [
                'CODE' => 'Code',
                'DESCRIPTION' => 'Description',
                'TYPE' => 'Type'
            ]
        );

        $this->updatedTYPE();

        try {

            $InfoMessage = '';

            if ($this->ID === 0) {
                $this->ID = $priceLevelServices->Store($this->CODE, $this->DESCRIPTION, $this->TYPE, $this->RATE, $this->ITEM_GROUP_ID, $this->INACTIVE);
                $InfoMessage = 'Successfully created.';
            } else {
                $priceLevelServices->Update($this->ID, $this->CODE, $this->DESCRIPTION, $this->TYPE, $this->RATE, $this->ITEM_GROUP_ID, $this->INACTIVE);
                $InfoMessage = 'Successfully updated.';
            }
            session()->flash('message', $InfoMessage);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function saveItem(PriceLevelLineServices $priceLevelLineServices)
    {
        $this->validate(
            [
                'ITEM_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('price_level_lines', 'item_id')->where(function ($query) {
                        return $query->where('price_level_id', $this->ID);
                    }),
                ],
                'CUSTOM_PRICE' => 'required|not_in:0,price_level_id',
            ],
            [],
            [
                'ITEM_ID' => 'Item',
                'CUSTOM_PRICE' => 'Custom Price'
            ]
        );

        $priceLevelLineServices->Store($this->ID, $this->ITEM_ID, $this->CUSTOM_PRICE);
        $this->priceLevelLines = $priceLevelLineServices->Search($this->search, $this->ID);
        $this->CUSTOM_PRICE = 0;
        $this->ITEM_ID = 0;
    }
    public function editItem($id, $custPrice)
    {
        $this->newCustomPrice = $custPrice;
        $this->editItemId = $id;
    }
    public function updateItem($id, PriceLevelLineServices $priceLevelLineServices)
    {
        $priceLevelLineServices->Update($id, $this->newCustomPrice);
        $this->editItemId = null;
        $this->priceLevelLines = $priceLevelLineServices->Search($this->search, $this->ID);
    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }
    public function deleteItem($id, PriceLevelLineServices $priceLevelLineServices): void
    {
        $priceLevelLineServices->Delete($id);
        $this->priceLevelLines = $priceLevelLineServices->Search($this->search, $this->ID);
    }
    public function updatedTYPE()
    {
        if ($this->TYPE === 1) {
            $this->RATE = 0;
            $this->ITEM_GROUP_ID = 0;
        }
    }

    public function render(PriceLevelLineServices $priceLevelLineServices)
    {
        $this->priceLevelLines = $priceLevelLineServices->Search($this->search, $this->ID);
        return view('livewire.price-level.price-level-form');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }


}
