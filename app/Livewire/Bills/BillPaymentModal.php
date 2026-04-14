<?php

namespace App\Livewire\Bills;

use App\Services\BillingServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillPaymentModal extends Component
{

    #[Reactive]
    public int $BILL_ID;
    public $showModal = false;
    private $billingServices;
    public $dataList = [];
    public function boot(BillingServices $billingServices)
    {
        $this->billingServices = $billingServices;
    }
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
        $this->dataList = $this->billingServices->PaymentHistory($this->BILL_ID);

        return view('livewire.bills.bill-payment-modal');
    }
}
