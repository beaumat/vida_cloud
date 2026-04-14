<?php

namespace App\Livewire\PatientPayment;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PatientPaymentCharges extends Component
{
    #[Reactive]
    public int $PATIENT_PAYMENT_ID;
    public $dataList = [];
    public int $STATUS;
    public int $openStatus;
    private $patientPaymentServices;
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    #[Reactive]
    public int $REF_ID;

    public $editPaymentChargeId = null;
    public int $editServiceChargeItemId;

    public float $editAmountApplied;
    public float $editItemAmount;
    public float $editItemPaid;

    public int $PHILHEALTH_ID = 0;
    public float $editAmountInit;
    private $serviceChargeServices;
    public function boot(
        PatientPaymentServices $patientPaymentServices,
        ServiceChargeServices $serviceChargeServices
    ) {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->serviceChargeServices = $serviceChargeServices;
    }
    public function edit(int $ID, int $SERVICE_CHARGES_ITEM_ID, float $Applied)
    {
        $data = $this->serviceChargeServices->getItem($SERVICE_CHARGES_ITEM_ID);

        if ($data) {
            $this->editPaymentChargeId = $ID;
            $this->editServiceChargeItemId = $SERVICE_CHARGES_ITEM_ID;
            $this->editAmountApplied = $Applied;
            $this->editAmountInit = $Applied;
            $this->editItemAmount = $data->AMOUNT;
            $this->editItemPaid = $data->PAID_AMOUNT - $Applied;
        }
    }

    public function cancel()
    {
        $this->editPaymentChargeId = null;
    }
    public function update()
    {
        $currentBalance = (float) $this->AMOUNT - ($this->AMOUNT_APPLIED - $this->editAmountInit);
        $currentAmount = $this->editItemAmount - $this->editItemPaid;

        if ($currentBalance < $this->editAmountApplied) {
            session()->flash('error', 'invalid payment initial. the remaining payment to low.');
            return;
        }

        if ($currentAmount < $this->editAmountApplied) {
            session()->flash('error', 'invalid payment initial. please enter exactly initial amount');
            return;
        }
        $this->patientPaymentServices->PaymentChargesUpdate($this->editPaymentChargeId, $this->PATIENT_PAYMENT_ID, $this->editServiceChargeItemId, 0, $this->editAmountApplied);
        $this->serviceChargeServices->updateServiceChargesItemPaid($this->editServiceChargeItemId);
        $this->editPaymentChargeId = null;
        $this->dispatch('reset-payment');
    }
    public function delete(int $ID, int $SERVICE_CHARGES_ITEM_ID)
    {
        $this->patientPaymentServices->PaymentChargesDelete($ID, $this->PATIENT_PAYMENT_ID, $SERVICE_CHARGES_ITEM_ID);
        $this->serviceChargeServices->updateServiceChargesItemPaid($SERVICE_CHARGES_ITEM_ID);

        $this->dispatch('reset-payment');
    }
    public function mount()
    {
  
    }

    public function render()
    {   

    
        $this->dataList = $this->patientPaymentServices->PaymentChargesList($this->PATIENT_PAYMENT_ID, $this->PHILHEALTH_ID);
    
        return view('livewire.patient-payment.patient-payment-charges');
    }
}
