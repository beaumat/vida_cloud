<?php

namespace App\Livewire\Option;

use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsFinance extends Component
{
    #[Reactive]
    public $systemSetting = [];
    public $accountList = [];
    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {   
        $this->systemSettingServices = $systemSettingServices;
    }
    public function mount()
    {
        
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
    public function saveOn($name, $value, SystemSettingServices $systemSettingServices)
    {
        $systemSettingServices->SetValue($name, $value);
        session()->flash('message', $name . ' HAS BEEN SAVE!');
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
        return view('livewire.option.option-settings-finance');
    }
}
