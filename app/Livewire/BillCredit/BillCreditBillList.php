<?php

namespace App\Livewire\BillCredit;

use App\Services\BillCreditServices;
use App\Services\BillingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillCreditBillList extends Component
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
    public float $ORG_AMOUNT;
    public $dataList = [];
    public $creditAmounts = [];
    private $billingServices;
    private $billCreditServices;

    public function boot(BillCreditServices $billCreditServices, BillingServices $billingServices)
    {
        $this->billCreditServices = $billCreditServices;
        $this->billingServices = $billingServices;
    }
    public function delete($ID, $BILL_ID)
    {
        $this->billCreditServices->BillCreditBillsDelete($ID, $this->BILL_CREDIT_ID, $BILL_ID);
        $this->billingServices->UpdateBalance($BILL_ID);
        $this->dispatch('reload-bill-credit');
    }
    #[On("reload-bill-credit")]
    public function updateAmount()
    {
        $getResult = $this->billCreditServices->ReComputed($this->BILL_CREDIT_ID);
        $this->dispatch('update-amount', result: $getResult);
        $this->dispatch('update-status');
    }
    public function render()
    {
        $this->ORG_AMOUNT = $this->AMOUNT;
        $this->dataList = $this->billCreditServices->BillCreditBillsList($this->BILL_CREDIT_ID);

        return view('livewire.bill-credit.bill-credit-bill-list');
    }
}
