<?php

namespace App\Livewire\Option;

use Illuminate\Support\Str;
use App\Models\SystemSetting;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Title('Option Settings')]

class OptionSettings extends Component
{
    public $systemSetting = [];
    public string $NAME;
    public string $VALUE;
    public string $activeTab = 'com';
    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {   
        $this->systemSettingServices = $systemSettingServices;
    }
    #[On('resetValue')]
    public function mount()
    {
        $this->systemSetting = DB::table('system_settings')->select(['NAME', 'VALUE'])->get();
    }
    public function save()
    {
        try {
            $this->systemSettingServices->SetValue($this->NAME, $this->VALUE);
            $this->systemSetting = SystemSetting::all();
        } catch (\Exception $e) {
            //throw $th;
            dd($e->getMessage());
        }
    }
    public function returnArray($name): string
    {

        foreach ($this->systemSetting as $list) {
            if (Str::lower($list->NAME) === Str::lower($name)) {
                return $list->VALUE;
            }
        }

        dd("record not found : " . $name);

    }
    public function getValue($name, SystemSettingServices $systemSettingServices): string
    {

        try {
            return $systemSettingServices->GetValue($name);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    public function SelectTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function render()
    {
        return view('livewire.option.option-settings');
    }
}
