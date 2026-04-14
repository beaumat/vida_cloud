<?php

namespace App\Livewire\WithHoldingTax;

use App\Services\BillingServices;
use App\Services\WithholdingTaxServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillListModal extends Component
{
    public $showModal = false;
    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $WITHHOLDING_TAX_ID;
    #[Reactive]
    public float $EWT_RATE;
    public $billList = [];
    public $selectedCharges = [];
    public $paymentAmounts = [];
    private $billingServices;
    private $withholdingTaxServices;

    public function boot(BillingServices $billingServices, WithholdingTaxServices $withholdingTaxServices)
    {
        $this->billingServices = $billingServices;
        $this->withholdingTaxServices = $withholdingTaxServices;
    }

    public function updatedSelectedCharges(bool $value, $id)
    {
        if (!$value) {
            $this->paymentAmounts[$id] = 0;
            return;
        }


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

        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                $ID = (int) $this->withholdingTaxServices->WTaxBillExists(
                    $this->WITHHOLDING_TAX_ID,
                    $chargeId
                );

                if ($ID > 0) {
                    $invData =  $this->billingServices->get($chargeId);
                    $chargeAmount =  $invData->AMOUNT * ($this->EWT_RATE / 100);
                    $this->withholdingTaxServices->UpdateBill(
                        $ID,
                        $this->WITHHOLDING_TAX_ID,
                        $chargeId,
                        $chargeAmount
                    );
                } else {
                    $data = $this->billingServices->get($chargeId);
                    if ($data) {
                        $chargeAmount =  $data->AMOUNT * ($this->EWT_RATE / 100);
        
                        $ACCOUNTS_PAYABLE_ID = $data->ACCOUNTS_PAYABLE_ID ?? 0;

                        $this->withholdingTaxServices->StoreBill(
                            $this->WITHHOLDING_TAX_ID,
                            $chargeId,
                            $chargeAmount,
                            $ACCOUNTS_PAYABLE_ID
                        );
                    }

                    $this->dispatch('reset-payment');
                }
                $this->billingServices->UpdateBalance($chargeId);
            }
        }

        $NEW_AMOUNT = $this->withholdingTaxServices->getTotal($this->WITHHOLDING_TAX_ID);

        $this->withholdingTaxServices->setTotal($this->WITHHOLDING_TAX_ID, $NEW_AMOUNT);
        $this->showModal = false;
        $this->selectedCharges = [];
        $this->paymentAmounts = [];
        $this->dispatch('reload_bill');
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
        $this->billList = $this->billingServices->getBillListViaWTax(
            $this->VENDOR_ID,
            $this->LOCATION_ID,
            $this->WITHHOLDING_TAX_ID
        );
        return view('livewire.with-holding-tax.bill-list-modal');
    }
}
