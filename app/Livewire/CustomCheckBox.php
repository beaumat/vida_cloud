<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CustomCheckBox extends Component
{
    #[Modelable]
    public $value = null;
    public string $name;
    #[Reactive]
    public string $titleName;
    #[Reactive]
    public bool $isDisabled;
    public bool $withLabel;
    public function mount($name, $titleName, $isDisabled = false, $withLabel = true)
    {
        $this->titleName = $titleName;
        $this->name = $name;
        $this->isDisabled = $isDisabled;
        $this->withLabel = $withLabel;
    }
    public function render()
    {
        return view('livewire.custom-check-box');
    }
}
