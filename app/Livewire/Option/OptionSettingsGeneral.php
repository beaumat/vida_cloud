<?php

namespace App\Livewire\Option;

use App\Models\Locations;
use App\Models\SystemSetting;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsGeneral extends Component
{
    #[Reactive]
    public $systemSetting = [];
    public $locationList = [];
    public $defaultDate = [];
    public string $DefaultLocationId;
    public string $NewTransactionsDefaultDate;
    public bool $LockDefaultLocation = false;
    public bool $IncRefNoByLocation = false;

    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {
        $this->systemSettingServices = $systemSettingServices;
    }

    public function mount()
    {
        $this->locationList = Locations::query()->select(['ID', 'NAME'])->where('INACTIVE', 0)->get();
        $this->defaultDate = [['ID' => '0', 'NAME' => 'Use today`s date'], ['ID' => '1', 'NAME' => 'Use the last entered date']];

        $this->DefaultLocationId = $this->returnArray('DefaultLocationId');
        $this->NewTransactionsDefaultDate = $this->returnArray('NewTransactionsDefaultDate');
        $this->LockDefaultLocation = (bool) $this->returnArray('LockDefaultLocation');
        $this->IncRefNoByLocation = (bool) $this->returnArray('IncRefNoByLocation');
        
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
    public function save()
    {

        if ($this->DefaultLocationId != $this->returnArray('DefaultLocationId')) {
            $this->saveOn("DefaultLocationId", $this->DefaultLocationId);
        }
        if ($this->NewTransactionsDefaultDate != $this->returnArray('NewTransactionsDefaultDate')) {
            $this->saveOn("NewTransactionsDefaultDate", $this->NewTransactionsDefaultDate);
        }
        if ($this->LockDefaultLocation != (bool) $this->returnArray('LockDefaultLocation')) {
            $this->saveOn("LockDefaultLocation", $this->LockDefaultLocation);
        }
        if ($this->IncRefNoByLocation != (bool) $this->returnArray('IncRefNoByLocation')) {
            $this->saveOn("IncRefNoByLocation", $this->IncRefNoByLocation);
        }

        $this->dispatch('resetValue');
        session()->flash('message', 'Save!');
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
        return view('livewire.option.option-settings-general');
    }
}
