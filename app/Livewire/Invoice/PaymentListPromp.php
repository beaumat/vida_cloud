<?php

namespace App\Livewire\Invoice;

use App\Services\InvoiceServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentListPromp extends Component
{
    #[Reactive]
    public int $INVOICE_ID;
    #[Reactive]
    public float $AMOUNT;
    public $showModal = false;
    private $invoiceServices;
    public $dataList = [];
    public function boot(InvoiceServices $invoiceServices)
    {
        $this->invoiceServices = $invoiceServices;
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
        $this->dataList = $this->invoiceServices->PaymentHistory($this->INVOICE_ID);
        
        return view('livewire.invoice.payment-list-promp');
    }
}
