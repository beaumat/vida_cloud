<?php

namespace App\Livewire\Option;

use App\Models\SystemSetting;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsAccounting extends Component
{
    #[Reactive]
    public $systemSetting = [];
    public $localSetting = [];
    public bool $SkipJournalEntry = false;
    public int $DateWarningDaysPast;
    public int $DateWarningDaysFuture;
    public string $ClosingDate;
    public string $SmallestCurrencyValue;
    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {   
        $this->systemSettingServices = $systemSettingServices;
    }
    public function mount()
    {
        $this->SkipJournalEntry = (bool) $this->returnArray('SkipJournalEntry');
        $this->DateWarningDaysPast = (int) $this->returnArray('DateWarningDaysPast');
        $this->DateWarningDaysFuture = (int) $this->returnArray('DateWarningDaysFuture');
        $this->ClosingDate = (string) $this->returnArray('ClosingDate');
        $this->SmallestCurrencyValue = (float) $this->returnArray('SmallestCurrencyValue');


    }

    public function save()
    {

        if ($this->SkipJournalEntry != (bool) $this->returnArray('SkipJournalEntry')) {
            $this->saveOn('SkipJournalEntry', $this->SkipJournalEntry);
        }
        if ($this->DateWarningDaysPast != (int) $this->returnArray('DateWarningDaysPast')) {
            $this->saveOn('DateWarningDaysPast', $this->DateWarningDaysPast);
        }

        if ($this->DateWarningDaysFuture != (int) $this->returnArray('DateWarningDaysFuture')) {
            $this->saveOn('DateWarningDaysFuture', $this->DateWarningDaysFuture);
        }

        if ($this->ClosingDate != (string) $this->returnArray('ClosingDate')) {
            $this->saveOn('ClosingDate', $this->ClosingDate);
        }

        if ($this->SmallestCurrencyValue != (float) $this->returnArray('SmallestCurrencyValue')) {

            $this->saveOn('SmallestCurrencyValue', $this->SmallestCurrencyValue);
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
        return view('livewire.option.option-settings-accounting');
    }
}
