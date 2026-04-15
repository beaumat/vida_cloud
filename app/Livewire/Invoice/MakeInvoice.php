<?php

namespace App\Livewire\Invoice;

use Livewire\Attributes\On;
use Livewire\Component;

class MakeInvoice extends Component
{

    public bool $showModal = false;
    public int $PATIENT_PAYMENT_ID;

    #[On('make-invoice-show')]
    public function openModal($result)
    {   
        $this->PATIENT_PAYMENT_ID = $result['PATIENT_PAYMENT_ID'];
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.invoice.make-invoice');
    }
}
