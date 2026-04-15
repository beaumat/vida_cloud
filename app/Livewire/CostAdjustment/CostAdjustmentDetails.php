<?php

namespace App\Livewire\CostAdjustment;

use App\Services\CostAdjustmentServices;
use App\Services\ItemServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CostAdjustmentDetails extends Component
{

    #[Reactive()]
    public int $COST_ADJUSTMENT_ID;
    #[Reactive]
    public int $STATUS;
    public int $openStatus = 0;
    public int $ITEM_ID;
    public float $COST;



    public float $EDIT_COST;

    public $itemList = [];
    public $editItemId = null;
    public bool $codeBase = false;
    public bool $saveSuccess = false;
    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public $itemDescList = [];
    public $itemCodeList = [];

    private $costAdjustmentServices;
    private $itemServices;
    public function boot(CostAdjustmentServices $costAdjustmentServices, ItemServices $itemServices)
    {
        $this->costAdjustmentServices = $costAdjustmentServices;
        $this->itemServices = $itemServices;
    }
    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getByCustomer(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getByCustomer(false);
    }
    public function updatedItemId()
    {

        $this->COST = 0;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';

        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;

            }
        }
    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }
    public function mount()
    {
        $this->COST = 0.00;
        $this->updatedcodeBase();
    }

    public function editItem($id)
    {
        $data = $this->costAdjustmentServices->GetItem($id);
        if ($data) {
            $this->editItemId = $data->ID;
            $this->EDIT_COST = $data->COST ?? 0;
        }
    }
    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID' => 'required|not_in:0|exists:item,id',
                'COST' => 'required|numeric|not_in:0'
            ],
            [],
            [
                'ITEM_ID' => 'Item',
                'COST' => 'Cost Amount'

            ]
        );

        try {
            DB::beginTransaction();
            $this->costAdjustmentServices->StoreItem($this->COST_ADJUSTMENT_ID, $this->ITEM_ID, $this->COST);
            $this->ITEM_ID = 0;
            $this->COST = 0;
            $this->ITEM_CODE = "";
            $this->ITEM_DESCRIPTION = "";
            
            $this->saveSuccess = $this->saveSuccess ? false : true;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }
    public function deleteItem(int $id)
    {
        $this->costAdjustmentServices->DeleteItem($id);
    }
    public function updateItem()
    {

        $this->validate(
            [

                'EDIT_COST' => 'required|numeric|not_in:0'
            ],
            [],
            [

                'EDIT_COST' => 'Cost Amount'

            ]
        );
        try {

            $this->costAdjustmentServices->UpdateItem($this->editItemId, $this->EDIT_COST);
            $this->editItemId = null;
        } catch (\Throwable $th) {
            //throw $th;

            session()->flash('error', $th->getMessage());
        }

    }
    public function reload()
    {
        $this->itemList = $this->costAdjustmentServices->ItemList($this->COST_ADJUSTMENT_ID);
    }
    public function render()
    {
        $this->reload();

        return view('livewire.cost-adjustment.cost-adjustment-details');
    }
}
