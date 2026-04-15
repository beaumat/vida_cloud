<?php

namespace App\Livewire\BillCredit;

use App\Services\BillCreditServices;
use App\Services\BillingServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillCreditBillModal extends Component
{
    public $showModal = false;
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
    public $dataList = [];
    public $selectedInvoices = [];
    public $creditAmounts = [];
    private $billingServices;
    private $billCreditServices;
    public function boot(BillingServices $billingServices, BillCreditServices $billCreditServices)
    {
        $this->billingServices = $billingServices;
        $this->billCreditServices = $billCreditServices;
    }
    public function mount(int $VENDOR_ID, int $LOCATION_ID, int $BILL_CREDIT_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->VENDOR_ID = $VENDOR_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->BILL_CREDIT_ID = $BILL_CREDIT_ID;
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
        $balance = $this->billingServices->getBalance($id);
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

        foreach ($this->selectedInvoices as $BillId => $isSelected) {
            if ($isSelected) {
                try {
                    $CollectAmount = $CollectAmount + $this->creditAmounts[$BillId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }

        if ($CollectAmount == 0) {
            session()->flash('error', 'Bill not selected.');
            return;
        }

        if ($CollectAmount > $CurrentAmount) {
            session()->flash('error', 'Invalid amount');
            return;
        }
        foreach ($this->selectedInvoices as $BillId => $isSelected) {
            if ($isSelected) {
                try {
                    $initialAmount = (float) $this->creditAmounts[$BillId] ?? 0;
                } catch (\Throwable $th) {
                    $initialAmount = 0;
                }


                if ($initialAmount) {
                    $ID = (int) $this->billCreditServices->BillCreditBillExists($this->BILL_CREDIT_ID, $BillId);
                    if ($ID > 0) {
                        $this->billCreditServices->BillCreditBillsUpdate($ID, $this->BILL_CREDIT_ID, $BillId, $initialAmount);
                    } else {
                        $this->billCreditServices->BillCreditBillsStore($this->BILL_CREDIT_ID, $BillId, $initialAmount);
                    }
                    $this->billingServices->UpdateBalance($BillId);

                }

            }
        }

        $this->updateCreditAmount();        
    }
    public function updateCreditAmount()
    {
        Redirect::route('vendorsbill_credit_edit', ['id' => $this->BILL_CREDIT_ID]);
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
        $this->dataList = $this->billingServices->getBillListViaBillCredit($this->VENDOR_ID, $this->LOCATION_ID, $this->BILL_CREDIT_ID);

        return view('livewire.bill-credit.bill-credit-bill-modal');
    }
}
