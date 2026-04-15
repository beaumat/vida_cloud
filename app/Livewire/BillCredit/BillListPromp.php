<?php

namespace App\Livewire\BillCredit;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillListPromp extends Component
{   

    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $BILL_CREDIT_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    public $showModal = false;
    
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.bill-credit.bill-list-promp');
    }
}
