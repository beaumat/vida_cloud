<?php

namespace App\Livewire\CreditMemo;

use App\Services\CreditMemoServices;
use App\Services\InvoiceServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CreditMemoInvoiceList extends Component
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
    public float $ORG_AMOUNT;
    public $invoiceList = [];
    public $selectedInvoices = [];
    public $creditAmounts = [];
    private $invoiceServices;
    private $creditMemoServices;
    
    public function boot(CreditMemoServices $creditMemoServices, InvoiceServices $invoiceServices)
    {
        $this->creditMemoServices = $creditMemoServices;
        $this->invoiceServices = $invoiceServices;
    }
    public function delete($ID, $INVOICE_ID)
    {
        $this->creditMemoServices->CreditMemoInvoiceDelete($ID,$this->CREDIT_MEMO_ID, $INVOICE_ID);
        $this->invoiceServices->updateInvoiceBalance($INVOICE_ID);

        $getResult = $this->creditMemoServices->ReComputed($this->CREDIT_MEMO_ID);
        $this->dispatch('update-amount', result: $getResult);
        $this->dispatch('update-status');
        
    }
    public function render()
    {   
        $this->ORG_AMOUNT = $this->AMOUNT;
        $this->invoiceList = $this->creditMemoServices->CreditMemoInvoiceList($this->CREDIT_MEMO_ID);
        return view('livewire.credit-memo.credit-memo-invoice-list');
    }
}
