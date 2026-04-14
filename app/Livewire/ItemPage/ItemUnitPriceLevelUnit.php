<?php

namespace App\Livewire\ItemPage;

use App\Models\ItemUnits;
use App\Models\PriceLevels;
use App\Services\ItemUnitPriceLevelServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemUnitPriceLevelUnit extends Component
{
    #[Reactive]
    public int $itemId = 0;
    public int $UnitRelatedId = 0;
    public $unitRelated = [];
    public int $PRICE_LEVEL_ID;
    public float $CUSTOM_PRICE;
    public $priceLevels = [];
    public $unitPriceLevels = [];
    public $search = null;
    public float $newCustomPrice;
    public bool $saveSuccess = false;
    public $editItemId = null;
    public bool $newRelated = false;

    private $itemUnitPriceLevelServices;
    public function boot(ItemUnitPriceLevelServices $itemUnitPriceLevelServices)
    {
        $this->itemUnitPriceLevelServices = $itemUnitPriceLevelServices;
    }
    public function UpdatedUnitRelatedId()
    {
        $this->unitPriceLevels =  $this->itemUnitPriceLevelServices->Search($this->UnitRelatedId);
    }

    #[On('reload-related')]
    public function RelatedDropdown()
    {
        $this->editItemId = null;
        $this->RelatedUnitLoad();
        $this->newRelated = $this->newRelated ? false : true;
    }
    public function RelatedUnitLoad()
    {
        $this->unitPriceLevels = [];
        $this->UnitRelatedId = 0;
        $this->unitRelated = ItemUnits::query()
            ->select([
                'item_units.ID',
                'unit_of_measure.NAME',
            ])
            ->join('unit_of_measure', 'unit_of_measure.ID', '=', 'item_units.UNIT_ID')
            ->where('item_units.ITEM_ID', $this->itemId)
            ->where('unit_of_measure.INACTIVE', '0')
            ->orderBy('item_units.ID', 'asc')
            ->get();
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->RelatedUnitLoad();
        $this->dropDownLoad();
    }
    public function dropDownLoad()
    {
        $this->priceLevels = PriceLevels::where('INACTIVE', '0')->get();
    }

    public function saveItem()
    {
        $this->validate(
            [
                'UnitRelatedId' => 'required|not_in:0',
                'PRICE_LEVEL_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('item_unit_price_levels', 'price_level_id')->where(function ($query) {
                        return $query->where('price_level_id', $this->PRICE_LEVEL_ID)->where('ITEM_UNIT_LINE_ID', $this->UnitRelatedId);
                    }),
                ],
                'CUSTOM_PRICE' => 'required|not_in:0',
            ],
            [],
            [
                'UnitRelatedId' => 'Related Unit',
                'PRICE_LEVEL_ID' => 'Price Level',
                'CUSTOM_PRICE' => 'Custom Price',
            ]
        );

        try {
            $this->itemUnitPriceLevelServices->Store($this->UnitRelatedId, $this->PRICE_LEVEL_ID, $this->CUSTOM_PRICE);

            $this->unitPriceLevels =  $this->itemUnitPriceLevelServices->Search($this->UnitRelatedId);
            $this->CUSTOM_PRICE = 0;
            $this->PRICE_LEVEL_ID = 0;
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->dropDownLoad();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function editItem($id, $custPrice)
    {
        $this->newCustomPrice = $custPrice;
        $this->editItemId = $id;
    }
    public function updateItem($id)
    {

        try {
            $this->itemUnitPriceLevelServices->Update($id, $this->newCustomPrice);
            $this->editItemId = null;
            $this->unitPriceLevels =  $this->itemUnitPriceLevelServices->Search($this->UnitRelatedId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem(): void
    {
        $this->editItemId = null;
    }
    public function deleteItem($id): void
    {

        try {
            $this->itemUnitPriceLevelServices->Delete($id);
            $this->unitPriceLevels =  $this->itemUnitPriceLevelServices->Search($this->UnitRelatedId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
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

        $this->unitPriceLevels = $this->itemUnitPriceLevelServices->Search($this->UnitRelatedId);

        return view('livewire.item-page.item-unit-price-level-unit');
    }
}
