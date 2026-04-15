<?php

namespace App\Livewire\ItemPage;

use App\Services\ItemUnitServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemUnitRelatedUnit extends Component
{
    #[Reactive]
    public int $itemId = 0;
    public int $UNIT_ID;
    public string $UNIT_SYMBOL;
    public float $QUANTITY;
    public float $RATE;
    public string $BARCODE;
    public bool $saveSuccess = false;
    public $units = [];
    public $unitRelatedList = [];
    public $editItemId = null;
    public float $newQUANTITY;
    public float $newRATE;
    public string $newBARCODE;
    private $unitOfMeasureServices;
    private $itemUnitServices;
    public function boot(UnitOfMeasureServices $unitOfMeasureServices, ItemUnitServices $itemUnitServices)
    {
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemUnitServices = $itemUnitServices;
    }
    private function getEmpty()
    {
        $this->UNIT_ID = 0;
        $this->QUANTITY = 0;
        $this->RATE = 0;
        $this->BARCODE = '';
        $this->UNIT_SYMBOL = '';
    }
    public function mount($itemId)
    {

        $this->itemId = $itemId;
        $this->getEmpty();
    }


    public function updatedUNITID()
    {   
        $data = $this->unitOfMeasureServices->get($this->UNIT_ID);
        if($data) {
            $this->UNIT_SYMBOL =  $data->SYMBOL ?? '';
        }

    }
    public function saveItem()
    {
        $this->validate(
            [
                'UNIT_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('item_units', 'unit_id')->where(function ($query) {
                        return $query->where('unit_id', $this->UNIT_ID)->where('item_id', $this->itemId);
                    }),
                ],
                'QUANTITY' => 'required|not_in:0,unit_id',
            ],
            [],
            [
                'UNIT_ID' => 'Unit',
                'QUANTITY' => 'Quantity',

            ]
        );
        try {
       
            DB::beginTransaction();
            $this->itemUnitServices->Store($this->itemId, $this->UNIT_ID, $this->QUANTITY, $this->RATE, $this->BARCODE);
            DB::commit();
            $this->getEmpty();
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->unitRelatedList = $this->itemUnitServices->Search($this->itemId);
            $this->dispatch('reload-related');
        } catch (\Exception $e) {
            DB::rollBack(); 
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function editItem(int $id, float $Qty, float $rate, string $barcode): void
    {

        $this->newQUANTITY = $Qty ? $Qty : 0;
        $this->newRATE = $rate ? $rate : 0;
        $this->newBARCODE = $barcode ? $barcode : '';
        $this->editItemId = $id;
    }
    public function updateItem($id): void
    {
        $this->validate(
            [
                'newQUANTITY' => 'required|not_in:0,unit_id',
        
            ],
            [],
            [
                'newQUANTITY' => 'Quantity'
            ]
        );

        try {
            $this->itemUnitServices->Update($id, $this->newQUANTITY, $this->newRATE, $this->newBARCODE);
            $this->editItemId = null;
            $this->unitRelatedList = $this->itemUnitServices->Search($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem(): void
    {
        $this->editItemId = null;
    }
    public function deleteItem(int $ID): void
    {
        try {
            $this->itemUnitServices->Delete($ID);
            $this->unitRelatedList = $this->itemUnitServices->Search($this->itemId);
            $this->dispatch('reload-related');
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
        $this->units = $this->unitOfMeasureServices->getList();

        $this->unitRelatedList = $this->itemUnitServices->Search($this->itemId);

        return view('livewire.item-page.item-unit-related-unit');
    }
}
