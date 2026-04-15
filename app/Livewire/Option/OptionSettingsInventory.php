<?php

namespace App\Livewire\Option;

use App\Models\StockType;
use App\Models\SystemSetting;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsInventory extends Component
{
    #[Reactive]
    public $systemSetting = [];
    public $stockTypeList = [];
    public $forcastType = [];
    public int $DefaultForecastingType;
    public int $DefaultItemStockType;
    public bool $DefaultItemTaxable;
    public int $SafetyStockPctLevel;
    public bool $ShowBatchNo, $ShowExpiryDate, $ShowLastPurchaseInfo, $ShowQtyOnSO, $ShowStockBin, $ShowUnitCost;
    public bool $AllowZeroOnHand, $LockQtyNeededInBuildAssembly, $SkipInventoryEntry;
    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {   
        $this->systemSettingServices = $systemSettingServices;
    }
    public function mount()
    {
        $this->stockTypeList = StockType::all();
        $this->DefaultItemStockType = (int) $this->returnArray('DefaultItemStockType');
        $this->DefaultItemTaxable = (bool) $this->returnArray('DefaultItemTaxable');

        $this->forcastType = [['ID' => 0, 'NAME' => 'Weekly'], ['ID' => 1, 'NAME' => 'Monthly']];

        $this->DefaultForecastingType = (int) $this->returnArray('DefaultForecastingType');
        $this->SafetyStockPctLevel = (int) $this->returnArray('SafetyStockPctLevel');

        $this->ShowBatchNo = (bool) $this->returnArray('ShowBatchNo');
        $this->ShowExpiryDate = (bool) $this->returnArray('ShowExpiryDate');
        $this->ShowLastPurchaseInfo = (bool) $this->returnArray('ShowLastPurchaseInfo');
        $this->ShowQtyOnSO = (bool) $this->returnArray('ShowQtyOnSO');
        $this->ShowStockBin = (bool) $this->returnArray('ShowStockBin');
        $this->ShowUnitCost = (bool) $this->returnArray('ShowUnitCost');

        $this->AllowZeroOnHand = (bool) $this->returnArray('AllowZeroOnHand');
        $this->LockQtyNeededInBuildAssembly = (bool) $this->returnArray('LockQtyNeededInBuildAssembly');
        $this->SkipInventoryEntry = (bool) $this->returnArray('SkipInventoryEntry');

    }
    public function save()
    {

        if ($this->DefaultItemStockType != (int) $this->returnArray('DefaultItemStockType')) {
            $this->saveOn("DefaultItemStockType", $this->DefaultItemStockType);
        }
        if ($this->DefaultItemTaxable != (bool) $this->returnArray('DefaultItemTaxable')) {
            $this->saveOn("DefaultItemTaxable", $this->DefaultItemTaxable);
        }
        if ($this->DefaultForecastingType != (int) $this->returnArray('DefaultForecastingType')) {
            $this->saveOn("DefaultForecastingType", $this->DefaultForecastingType);
        }
        if ($this->SafetyStockPctLevel != (int) $this->returnArray('SafetyStockPctLevel')) {
            $this->saveOn("SafetyStockPctLevel", $this->SafetyStockPctLevel);
        }
        if ($this->ShowBatchNo != (bool) $this->returnArray('ShowBatchNo')) {
            $this->saveOn("ShowBatchNo", $this->ShowBatchNo);
        }
        if ($this->ShowExpiryDate != (bool) $this->returnArray('ShowExpiryDate')) {
            $this->saveOn("ShowExpiryDate", $this->ShowExpiryDate);
        }
        if ($this->ShowLastPurchaseInfo != (bool) $this->returnArray('ShowLastPurchaseInfo')) {
            $this->saveOn("ShowLastPurchaseInfo", $this->ShowLastPurchaseInfo);
        }
        if ($this->ShowQtyOnSO != (bool) $this->returnArray('ShowQtyOnSO')) {
            $this->saveOn("ShowQtyOnSO", $this->ShowQtyOnSO);
        }
        if ($this->ShowStockBin != (bool) $this->returnArray('ShowStockBin')) {
            $this->saveOn("ShowStockBin", $this->ShowStockBin);
        }
        if ($this->ShowUnitCost != (bool) $this->returnArray('ShowUnitCost')) {
            $this->saveOn("ShowUnitCost", $this->ShowUnitCost);
        }
        if ($this->AllowZeroOnHand != (bool) $this->returnArray('AllowZeroOnHand')) {
            $this->saveOn("AllowZeroOnHand", $this->AllowZeroOnHand);
        }
        if ($this->LockQtyNeededInBuildAssembly != (bool) $this->returnArray('LockQtyNeededInBuildAssembly')) {
            $this->saveOn("LockQtyNeededInBuildAssembly", $this->LockQtyNeededInBuildAssembly);
        }
        if ($this->SkipInventoryEntry != (bool) $this->returnArray('SkipInventoryEntry')) {
            $this->saveOn("SkipInventoryEntry", $this->SkipInventoryEntry);
        }

        $this->dispatch('resetValue');
        session()->flash('message', 'Save!');
    }
    public function returnArray($name): string
    {
        foreach ($this->systemSetting as $list) {
            if (Str::lower($list->NAME) == Str::lower($name)) {
                return $list->VALUE;
            }
        }
        $this->systemSettingServices->NewValue($name);
        dd("record not found : " . $name);
        return '';
    }
    public function saveOn($name, $value)
    {
        SystemSetting::where('NAME', $name)->update(['VALUE' => $value]);
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
        return view('livewire.option.option-settings-inventory');
    }
}
