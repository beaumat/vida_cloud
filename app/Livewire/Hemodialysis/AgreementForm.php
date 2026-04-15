<?php

namespace App\Livewire\Hemodialysis;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Agreement Form')]
class AgreementForm extends Component
{
    public $HEMO_ID;
    public function mount($id)
    {
        $this->HEMO_ID = $id;

        $this->getAuto();


        $this->dispatch('preview_print');
    }
    private function getAuto() {

    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.hemodialysis.agreement-form');
    }
}
