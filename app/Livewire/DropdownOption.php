<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DropdownOption extends Component
{
    #[Modelable]
    public $value = null;
    public string $name;
    #[Reactive]
    public string $titleName;
    #[Reactive]
    public $options = [];
    public bool $zero;
    public bool $vertical;
    public bool $withLabel;
    #[Reactive]
    public bool $isDisabled;
    public function mount($name, $options, $zero, $titleName, $vertical = false, $withLabel = true, $isDisabled = false)
    {
        $this->titleName = $titleName;
        $this->zero = $zero;
        $this->name = $name;
        $this->options = $options;
        $this->vertical = $vertical;
        $this->withLabel = $withLabel;
        $this->isDisabled = $isDisabled;
    }
    public function render()
    {
        return view('livewire.dropdown-option');
    }
}
