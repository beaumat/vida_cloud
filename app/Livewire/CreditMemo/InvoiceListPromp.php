<?php

namespace App\Livewire\CreditMemo;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class InvoiceListPromp extends Component
{
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $CREDIT_MEMO_ID;
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
        return view('livewire.credit-memo.invoice-list-promp');
    }
}
