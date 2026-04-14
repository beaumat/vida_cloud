<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CalculatorModal extends Component
{



    public string $NAME;
    public float $AMOUNT = 0.0;
    public bool $showModal = false;

    public $data;

    #[On('open-calculator')]
    public function openModal($data)
    {
        $this->AMOUNT = (float) $data['AMOUNT'];
        $this->NAME = $data['NAME'];
        $this->showModal = true;
    }
    public function closeModal()
    {
        $data = [
            'AMOUNT' => 400,
            'NAME' => $this->NAME
        ];
        $this->dispatch('return-from', data: $data);
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.calculator-modal');
    }
}
