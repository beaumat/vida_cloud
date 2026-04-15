<?php

namespace App\Livewire\BillPayments;

use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillModal extends Component
{
    public $showModal = false;
    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $CHECK_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    #[Reactive]
    public bool $SAME_AMOUNT;
    #[Reactive]
    public int $PF_PERIOD_ID;


    public $invoiceList = [];
    public $selectedCharges = [];
    public $paymentAmounts = [];
    private $billingServices;
    private $billPaymentServices;

    public function boot(
        BillingServices $billingServices,
        BillPaymentServices $billPaymentServices
    ) {
        $this->billingServices = $billingServices;
        $this->billPaymentServices = $billPaymentServices;
    }
    public function mount(int $VENDOR_ID, int $LOCATION_ID, int $CHECK_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->VENDOR_ID = $VENDOR_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->CHECK_ID = $CHECK_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
    }
    

    public function updatedSelectedCharges(bool $value, $id)
    {
        if (!$value) {
            $this->paymentAmounts[$id] = 0;
            return;
        }

        if (!$this->SAME_AMOUNT) {
            $CurrentAmount = (float) $this->AMOUNT - $this->AMOUNT_APPLIED;
            $CollectAmount = 0;
            foreach ($this->selectedCharges as $chargeId => $isSelected) {
                if ($isSelected) {
                    try {
                        $CollectAmount = $CollectAmount + $this->paymentAmounts[$chargeId] ?? 0;
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
        } else {
            $mustPay = $this->billingServices->getBalance($id);
        }



        $this->paymentAmounts[$id] = $mustPay;
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

        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $CollectAmount = $CollectAmount + $this->paymentAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }


        if (!$this->SAME_AMOUNT) {
            if ($CollectAmount > $CurrentAmount) {
                session()->flash('error', 'Invalid amount');
                return;
            }
        }

        if ($CollectAmount == 0) {
            session()->flash('error', 'bill payment selected not found.');
            return;
        }


        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $chargeAmount = $this->paymentAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $chargeAmount = 0;
                }

                if ($chargeAmount) {
                    $ID = (int) $this->billPaymentServices->BillPaymentBillsExist($this->CHECK_ID, $chargeId); // if already added
                    if ($ID > 0) {
                        $this->billPaymentServices->billPaymentBills_Update($ID, $this->CHECK_ID, $chargeId, 0, $chargeAmount);
                    } else {
                        $bill = $this->billingServices->get($chargeId);
                        if ($bill) {
                            $this->billPaymentServices->billPaymentBills_Store($this->CHECK_ID, $chargeId, 0, $chargeAmount, 0, $bill->ACCOUNTS_PAYABLE_ID ?? 0);
                        }
                    }
                    $this->billingServices->UpdateBalance($chargeId);
                    $this->dispatch('reset-payment');
                }
            }
        }

        $this->showModal = false;
        $this->selectedCharges = [];
        $this->paymentAmounts = [];
        $this->SetAmount();
        $this->dispatch('reload_bill_list');
    }
    private function SetAmount()
    {
        $AMOUNT = (float) $this->billPaymentServices->getTotalApplied($this->CHECK_ID);
        $this->billPaymentServices->UpdateAmount($this->CHECK_ID, $AMOUNT);
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
        if($this->showModal) {
            if ($this->PF_PERIOD_ID > 0) {
                $this->invoiceList = $this->billingServices->getBillListViaBillPaymentExistOnPhilealth($this->VENDOR_ID, $this->LOCATION_ID, $this->CHECK_ID, $this->PF_PERIOD_ID);
            } else {
                $this->invoiceList = $this->billingServices->getBillListViaBillPayment($this->VENDOR_ID, $this->LOCATION_ID, $this->CHECK_ID);
            }
        }
        return view('livewire.bill-payments.bill-modal');
    }
}
