<?php
namespace App\Livewire\FixedAssetItem;

use App\Services\DepreciationServices;
use App\Services\FixedAssetItemServices;
use Livewire\Attributes\On;
use Livewire\Component;

class FixedAssetDepreciation extends Component
{
    public bool $showModal = false;
    public int $FIXED_ASSET_ITEM_ID;

    private $fixedAssetItemServices;
    private $depreciationServices;
    public $dataList = [];
    public function boot(FixedAssetItemServices $fixedAssetItemServices, DepreciationServices $depreciationServices)
    {
        $this->fixedAssetItemServices = $fixedAssetItemServices;
        $this->depreciationServices   = $depreciationServices;
    }

    #[On("open-depreciation")]
    public function openModal($result)
    {
        $this->FIXED_ASSET_ITEM_ID = $result['ID'];
        $this->showModal           = true;
        $this->getDetails();
    }

    private function getDetails()
    {
        $this->dataList = $this->depreciationServices->ShowAssetHistory($this->FIXED_ASSET_ITEM_ID);
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.fixed-asset-item.fixed-asset-depreciation');
    }
}
