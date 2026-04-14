<?php

namespace App\Livewire\Option;

use App\Models\SystemSetting;
use App\Models\Tax;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsTax extends Component
{
    #[Reactive]
    public $systemSetting = [];
    public string $CompanyTin;
    public int $OutputTaxId;
    public int $InputTaxId;
    public $taxList = [];
    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {
        $this->systemSettingServices = $systemSettingServices;
    }
    public function mount()
    {
        $this->taxList = Tax::query()->select(['ID', 'NAME'])->where('TAX_TYPE', 3)->get();
        $this->CompanyTin = $this->returnArray('CompanyTin');
        $this->OutputTaxId = (int) $this->returnArray('OutputTaxId');
        $this->InputTaxId = (int) $this->returnArray('InputTaxId');
    }
    public function save()
    {

        if ($this->CompanyTin != $this->returnArray('CompanyTin')) {
            $this->saveOn("CompanyTin", $this->CompanyTin);
        }
        if ($this->OutputTaxId != (int) $this->returnArray('OutputTaxId')) {
  
            $this->saveOn("OutputTaxId", $this->OutputTaxId);
        }
        if ($this->InputTaxId != (int) $this->returnArray('InputTaxId')) {
            $this->saveOn("InputTaxId", $this->InputTaxId);
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
        return view('livewire.option.option-settings-tax');
    }
}
