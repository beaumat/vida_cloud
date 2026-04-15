<?php

namespace App\Livewire\Patient;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Philhealth Availment')]
class PrintAvailment extends Component
{
    public $id;
    public $year;
    public $locationid;

    public function mount($id = null, int $year, int $locationid)
    {
        $this->id = $id;
        $this->year = $year;
        $this->locationid = $locationid;
        $this->dispatch('preview_print');
    }

    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.patient.print-availment');
    }
}
