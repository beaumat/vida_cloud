<?php

namespace App\Livewire\Hemodialysis;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Hemodialysis Treatment - Print Back Form')]
class PrintFormBackOne extends Component
{

    public $HEMO_ID = [];
    public function mount($id)
    {

        if (!$id) {
            $this->HEMO_ID = [];
            return;
        }

        $this->HEMO_ID = explode(',', $id);
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.hemodialysis.print-form-back-one');
    }
}
