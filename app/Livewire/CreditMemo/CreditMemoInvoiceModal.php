<?php

namespace App\Livewire\CreditMemo;

use App\Services\CreditMemoServices;
use App\Services\InvoiceServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CreditMemoInvoiceModal extends Component
{
    public $showModal = false;
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
    public $invoiceList = [];
    public $selectedInvoices = [];
    public $creditAmounts = [];
    private $invoiceServices;
    private $creditMemoServices;
    public function boot(InvoiceServices $invoiceServices, CreditMemoServices $creditMemoServices)
    {
        $this->invoiceServices = $invoiceServices;
        $this->creditMemoServices = $creditMemoServices;
    }
    public function mount(int $CUSTOMER_ID, int $LOCATION_ID, int $CREDIT_MEMO_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->CUSTOMER_ID = $CUSTOMER_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->CREDIT_MEMO_ID = $CREDIT_MEMO_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
    }
    public function updatedselectedInvoices(bool $value, $id)
    {
        if (!$value) {
            $this->creditAmounts[$id] = 0;
            return;
        }
        $CurrentAmount = (float) $this->AMOUNT - $this->AMOUNT_APPLIED;
        $CollectAmount = 0;
        foreach ($this->selectedInvoices as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $CollectAmount = $CollectAmount + $this->creditAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }
        $newPay = $CurrentAmount - $CollectAmount;
        $balance = $this->invoiceServices->getBalance($id);
        if ($balance <= $newPay) {
            $mustPay = $balance;
        } else {
            $mustPay = $newPay;
        }
        $this->creditAmounts[$id] = $mustPay;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save()
    {
        $CurrentAmount = (float) $this->AMOUNT - $this->AMOUNT_APPLIED;
        $CollectAmount = 0;
        //Check Amount First
        foreach ($this->selectedInvoices as $InvoiceId => $isSelected) {
            if ($isSelected) {
                try {
                    $CollectAmount = $CollectAmount + $this->creditAmounts[$InvoiceId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }

        if ($CollectAmount == 0) {
            session()->flash('error', 'Invoice not selected.');
            return;
        }

        if ($CollectAmount > $CurrentAmount) {
            session()->flash('error', 'Invalid amount');
            return;
        }
        foreach ($this->selectedInvoices as $InvoiceId => $isSelected) {
            if ($isSelected) {
                try {
                    $initialAmount = (float) $this->creditAmounts[$InvoiceId] ?? 0;
                } catch (\Throwable $th) {
                    $initialAmount = 0;
                }

                if ($initialAmount) {
                    $ID = (int) $this->creditMemoServices->CreditMemoInvoiceExist($this->CREDIT_MEMO_ID, $InvoiceId);
                    if ($ID > 0) {
                        $this->creditMemoServices->CreditMemoInvoiceUpdate($ID, $this->CREDIT_MEMO_ID, $InvoiceId, $initialAmount);
                        $this->invoiceServices->updateInvoiceBalance($InvoiceId);

                    } else {
                        $this->creditMemoServices->CreditMemoInvoiceStore($this->CREDIT_MEMO_ID, $InvoiceId,  $initialAmount);
                        $this->invoiceServices->updateInvoiceBalance($InvoiceId);

                    }
     
                }

            }
        }

        $this->showModal = false;
        $this->selectedInvoices = [];
        $this->creditAmounts = [];
        $getResult = $this->creditMemoServices->ReComputed($this->CREDIT_MEMO_ID);
        $this->dispatch('update-amount', result: $getResult);
        $this->dispatch('update-status');
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function render()
    {
        $this->invoiceList = $this->invoiceServices->getInvoiceListViaCreditMemo($this->CUSTOMER_ID, $this->LOCATION_ID, $this->CREDIT_MEMO_ID);
        
        return view('livewire.credit-memo.credit-memo-invoice-modal');
    }
}
