<?php

namespace App\Livewire\Payment;

use App\Services\InvoiceServices;
use App\Services\PaymentServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InvoiceListModal extends Component
{
    public $showModal = false;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $PAYMENT_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    public $invoiceList = [];
    public $selectedCharges = [];
    public $paymentAmounts = [];
    private $invoiceServices;
    private $paymentServices;

    public function boot(InvoiceServices $invoiceServices, PaymentServices $paymentServices)
    {
        $this->invoiceServices = $invoiceServices;
        $this->paymentServices = $paymentServices;
    }
    public function mount(int $CUSTOMER_ID, int $LOCATION_ID, int $PAYMENT_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->CUSTOMER_ID = $CUSTOMER_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->PAYMENT_ID = $PAYMENT_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
    }
    public function updatedSelectedCharges(bool $value, $id)
    {
        if (!$value) {
            $this->paymentAmounts[$id] = 0;
            return;
        }

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
        $balance = $this->invoiceServices->getBalance($id);
        if ($balance <= $newPay) {
            $mustPay = $balance;
        } else {
            $mustPay = $newPay;
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
        //Check Amount First
        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $CollectAmount = $CollectAmount + $this->paymentAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }

        if ($CollectAmount == 0) {
            session()->flash('error', 'payment selected not found.');
            return;
        }

        if ($CollectAmount > $CurrentAmount) {
            session()->flash('error', 'Invalid amount');
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
                    $ID = (int) $this->paymentServices->PaymentInvoiceExist(
                        $this->PAYMENT_ID,
                        $chargeId
                    );
                    if ($ID > 0) {
                        $this->paymentServices->PaymentInvoiceUpdate(
                            $ID,
                            $this->PAYMENT_ID,
                            $chargeId,
                            0,
                            $chargeAmount
                        );
                        $this->invoiceServices->updateInvoiceBalance($chargeId);
                    } else {
                        $data = $this->invoiceServices->get($chargeId);
                        if ($data) {
                            $ACCOUNTS_RECEIVABLE_ID = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;

                            $this->paymentServices->PaymentInvoiceStore(
                                $this->PAYMENT_ID,
                                $chargeId,
                                0,
                                $chargeAmount,
                                0,
                                $ACCOUNTS_RECEIVABLE_ID
                            );
                            $this->invoiceServices->updateInvoiceBalance($chargeId);
                        }
                    }
                    $this->dispatch('reset-payment');
                }
            }
        }

        $this->showModal = false;
        $this->selectedCharges = [];
        $this->paymentAmounts = [];
        $this->dispatch('reload_payment_invoice');
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
        $this->invoiceList = $this->invoiceServices->getInvoiceListViaPayment(
            $this->CUSTOMER_ID,
            $this->LOCATION_ID,
            $this->PAYMENT_ID
        );

        return view('livewire.payment.invoice-list-modal');
    }
}
