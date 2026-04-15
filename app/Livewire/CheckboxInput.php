<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CheckboxInput extends Component
{
    #[Modelable]
    public $value = null;
    public string $name;
    public string $titleName;
    #[Reactive]
    public bool $isDisabled;
    public function mount($name, $titleName,  $isDisabled = false)
    {
        $this->titleName = $titleName;
        $this->name = $name;
        $this->isDisabled = $isDisabled;
    }
    public function render()
    {
        return view('livewire.checkbox-input');
    }
}
