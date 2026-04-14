<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class NumberInput extends Component
{
    #[Modelable]
    public $value = null;
    public string $name = "";
    #[Reactive]
    public string $titleName;
    public bool $vertical;
    public bool $withLabel;
    #[Reactive]
    public bool $isDisabled;

    public $isFocused = false;
    public $showCalculator = false;
    public function mount($name, $titleName, $vertical = false, $withLabel = true, $isDisabled = false)
    {
        $this->titleName = $titleName;
        $this->name = $name;
        $this->vertical = $vertical;
        $this->withLabel = $withLabel;
        $this->isDisabled = $isDisabled;
    }
    public function OpenCalculator()
    {
        $data = [
            'AMOUNT' => $this->value ?? 0,
            'NAME'  => $this->name
        ];
        $this->dispatch("open-calculator", data: $data);
    }
    #[On('return-from')]
    public function dataReturn($data)
    {   
    
        if ($this->name == $data['NAME']) {
            $this->value = $data['AMOUNT'] ?? 0;

            
        }
    }
    public function render()
    {
        return view('livewire.number-input');
    }
}
