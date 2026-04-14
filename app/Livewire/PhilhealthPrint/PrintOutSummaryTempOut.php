<?php

namespace App\Livewire\PhilhealthPrint;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('PhilHealth Printing Treatment Summary (Pre-sign) Output')]
class PrintOutSummaryTempOut extends Component
{   

    public $PRINT_ID = [];

    public function mount($id)
    {

        if (!$id) {
            $this->PRINT_ID = [];
            return;
        }
        $this->PRINT_ID = explode(',', $id);
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.philhealth-print.print-out-summary-temp-out');
    }
}
