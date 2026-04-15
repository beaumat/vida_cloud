<?php
namespace App\Livewire\Depreciation;

use App\Services\DepreciationServices;
use App\Services\FixedAssetItemServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DepreciationItems extends Component
{

    #[Reactive]
    public int $DEPRECIATION_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $STATUS;
    public bool $saveSuccess = false;
    public int $FIXED_ASSET_ITEM_ID;
    public float $AMOUNT = 0;
    public int $ACCOUNT_ID;
    public $fixedAssetItemList = [];
    public $dataList = [];
    private $depreciationServices;
    private $fixedAssetItemServices;

    public function boot(DepreciationServices $depreciationServices, FixedAssetItemServices $fixedAssetItemServices)
    {
        $this->depreciationServices   = $depreciationServices;
        $this->fixedAssetItemServices = $fixedAssetItemServices;
    }
    public function mount()
    {

    }
    public function UpdatedFixedAssetItemId()
    {
        if ($this->FIXED_ASSET_ITEM_ID > 0) {
            $result = $this->fixedAssetItemServices->Get($this->FIXED_ASSET_ITEM_ID);

            if ($result) {
                $USEFULL_LIFE = (float) $result->USEFUL_LIFE ?? 0;
                $AQ_COST      = (float) $result->AQ_COST ?? 0;
                if ($AQ_COST > 0) {
                    $PER_YEAR     = (float) $AQ_COST / $USEFULL_LIFE;
                    $PER_MONTH    = (float) $PER_YEAR / 12;
                    $this->AMOUNT = round( $PER_MONTH,2);
                    return;
                }

            }

            $this->AMOUNT = 0;
            return;
        }

        $this->AMOUNT = 0;
    }
    public function add()
    {

        $this->validate(
            [
                'FIXED_ASSET_ITEM_ID' => 'required|numeric|exists:fixed_asset_item,id',
                'AMOUNT'              => 'required|numeric|not_in:0',
            ],
            [],
            [
                'FIXED_ASSET_ITEM_ID' => 'Fixed asset item',
                'AMOUNT'              => 'Amount',
            ]
        );

        $this->ACCOUNT_ID = 0;
        $data             = $this->fixedAssetItemServices->Get($this->FIXED_ASSET_ITEM_ID);
        if ($data) {
            $this->ACCOUNT_ID = (int) $data->ACCUMULATED_ACCOUNT_ID ?? 0;
        } else {
            session()->flash('error', 'Fixed Asset Item not found.');
            return;
        }

        if ($this->ACCOUNT_ID == 0) {
            session()->flash('error', 'Accumulated Account not found.');
            return;
        }

        if ($this->depreciationServices->ItemExistsAdded($this->DEPRECIATION_ID, $this->FIXED_ASSET_ITEM_ID)) {
            session()->flash('error', 'This assist item is already exists');
            return;
        }

        try {
            $this->depreciationServices->ItemStore(
                $this->DEPRECIATION_ID,
                $this->FIXED_ASSET_ITEM_ID,
                $this->AMOUNT,
                $this->ACCOUNT_ID
            );
            $this->UpdateTotal();
            // clear field to success
            $this->FIXED_ASSET_ITEM_ID = 0;
            $this->AMOUNT              = 0;
            $this->ACCOUNT_ID          = 0;
            $this->saveSuccess         = $this->saveSuccess ? false : true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function UpdateTotal()
    {
        $this->depreciationServices->Recomputed($this->DEPRECIATION_ID);
        $this->dispatch('refresh-amount');
    }
    public $editID = null;
    public $editAmount = 0;

    public function edit(int $ID)
    {
        $data = $this->depreciationServices->ItemGet($ID);
        if ($data) {
            $this->editID     = $data->ID ?? 0;
            $this->editAmount = number_format($data->AMOUNT, 2);
        }
    }
    public function update()
    {
        $this->validate(
            [
                'editAmount' => 'required|numeric|not_in:0',
            ],
            [],
            [
                'editAmount' => 'Amount',
            ]
        );

        try {
            $this->depreciationServices->ItemUpdate($this->editID, $this->editAmount);
            $this->UpdateTotal();
            $this->cancel();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function delete(int $ID)
    {
        try {
            $this->depreciationServices->ItemDelete($ID);
            $this->UpdateTotal();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancel()
    {
        $this->editID     = null;
        $this->editAmount = 0;
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
        $this->fixedAssetItemList = $this->fixedAssetItemServices->getList($this->LOCATION_ID);
        $this->dataList           = $this->depreciationServices->ItemList($this->DEPRECIATION_ID);
        return view('livewire.depreciation.depreciation-items');
    }
}
