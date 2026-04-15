<?php

namespace App\Livewire\SalesReceipt;

use Livewire\Attributes\On;
use Livewire\Component;

class MakeSalesReceipt extends Component
{
    public bool $showModal = false;
    public int $PATIENT_PAYMENT_ID;

    #[On('make-sales-receipt-show')]
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
        return view('livewire.sales-receipt.make-sales-receipt');
    }
}
