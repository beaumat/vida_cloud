<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DateInput extends Component
{
    #[Modelable]
    public $value = null;
    public string $name;
    #[Reactive]
    public string $titleName;
    public bool $vertical;
    public bool $withLabel;
    #[Reactive]
    public bool $isDisabled;
    public function mount($name, $titleName, $vertical = false, $withLabel = true, $isDisabled = false)
    {
        $this->titleName = $titleName;
        $this->name = $name;
        $this->vertical = $vertical;
        $this->withLabel = $withLabel;
        $this->isDisabled = $isDisabled;
    }
    public function render()
    {
        return view('livewire.date-input');
    }
}
