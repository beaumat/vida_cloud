<?php

namespace App\Livewire\Option;

use App\Models\SystemSetting;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsCompany extends Component
{

    #[Reactive]
    public $systemSetting = [];
    public string $CompanyName;
    public string $CompanyAddress;
    public string $CompanyEmailAddress;
    public string $CompanyFaxNo;
    public string $CompanyMobileNo;
    public string $CompanyPhoneNo;
    public string $CompanyTin;
    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {   
        $this->systemSettingServices = $systemSettingServices;
    }

    public function mount(SystemSettingServices $systemSettingServices)
    {

        $this->CompanyName = $this->returnArray('CompanyName');
        $this->CompanyAddress = $this->returnArray('CompanyAddress');
        $this->CompanyEmailAddress = $this->returnArray('CompanyEmailAddress');
        $this->CompanyFaxNo = $this->returnArray('CompanyFaxNo');
        $this->CompanyMobileNo = $this->returnArray('CompanyMobileNo');
        $this->CompanyPhoneNo = $this->returnArray('CompanyPhoneNo');
        $this->CompanyTin = $this->returnArray('CompanyTin');
    }
    public function save()
    {
        if ($this->CompanyName != $this->returnArray('CompanyName')) {
            $this->saveOn('CompanyName', $this->CompanyName);
        }
        if ($this->CompanyAddress != $this->returnArray('CompanyAddress')) {
            $this->saveOn('CompanyAddress', $this->CompanyAddress);
        }
        if ($this->CompanyEmailAddress != $this->returnArray('CompanyEmailAddress')) {
            $this->saveOn('CompanyEmailAddress', $this->CompanyEmailAddress);
        }
        if ($this->CompanyFaxNo != $this->returnArray('CompanyFaxNo')) {
            $this->saveOn('CompanyFaxNo', $this->CompanyFaxNo);
        }
        if ($this->CompanyMobileNo != $this->returnArray('CompanyMobileNo')) {
            $this->saveOn('CompanyMobileNo', $this->CompanyMobileNo);
        }
        if ($this->CompanyPhoneNo != $this->returnArray('CompanyPhoneNo')) {
            $this->saveOn('CompanyPhoneNo', $this->CompanyPhoneNo);
        }
        if ($this->CompanyTin != $this->returnArray('CompanyTin')) {
            $this->saveOn('CompanyTin', $this->CompanyTin);
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
        return view('livewire.option.option-settings-company');
    }
}
