<?php

namespace App\Livewire\PhilHealth;

use App\Services\PatientPaymentServices;
use App\Services\PhilHealthServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ServicesChargesModal extends Component
{

    public int $PHILHEALTH_ID;
    public int $LOCATION_ID;
    public int $PATIENT_ID;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $PATIENT_PAYMENT_ID;
    public bool $showModal = false;
    public $dataList = [];

    public $selectedCharges = [];
    public $paymentAmounts = [];
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;

    private $serviceChargeServices;
    private $philHealthServices;
    private $patientPaymentServices;
    public function boot(ServiceChargeServices $serviceChargeServices, PhilHealthServices $philHealthServices, PatientPaymentServices $patientPaymentServices)
    {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->philHealthServices = $philHealthServices;
        $this->patientPaymentServices = $patientPaymentServices;
    }

    #[On('open-payment-sc')]
    public function openModal($result)
    {

        $this->SelectAllCharges = false;

        $this->PATIENT_PAYMENT_ID = $result['PATIENT_PAYMENT_ID'];

        $pay = $this->patientPaymentServices->get($this->PATIENT_PAYMENT_ID);

        if ($pay) {

            $this->AMOUNT = $pay->AMOUNT ?? 0;
            $this->AMOUNT_APPLIED = $pay->AMOUNT_APPLIED ?? 0;

            $data = $this->philHealthServices->get($this->PHILHEALTH_ID);
            if ($data) {
                $this->PATIENT_ID =  $data->CONTACT_ID;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->DATE_FROM = $data->DATE_ADMITTED;
                $this->DATE_TO  = $data->DATE_DISCHARGED;
                $this->showModal = true;
            }
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save(): void
    {
        $CurrentAmount = (float) $this->AMOUNT - $this->AMOUNT_APPLIED;
        $CollectAmount = 0;
        //Check Amount First
        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $init_value = $this->paymentAmounts[$chargeId] ?? 0;
                    $CollectAmount = $CollectAmount + $init_value;
                    if ($this->patientPaymentServices->gotHaveItemBalance($this->dataList, $chargeId, $init_value) == true) {
                        session()->flash('error', 'invalid payment initial. please enter exactly initial amount');
                        return;
                    }
                } catch (\Throwable $th) {
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
                    $ID = (int) $this->patientPaymentServices->PaymentChargesExist($this->PATIENT_PAYMENT_ID, $chargeId);
                    if ($ID > 0) {
                        $this->patientPaymentServices->PaymentChargesUpdate($ID, $this->PATIENT_PAYMENT_ID, $chargeId, 0, $chargeAmount);
                        $this->serviceChargeServices->updateServiceChargesItemPaid($chargeId);
                    } else {
                        $this->patientPaymentServices->PaymentChargeStore($this->PATIENT_PAYMENT_ID, $chargeId, 0, $chargeAmount, 0, 0);
                        $this->serviceChargeServices->updateServiceChargesItemPaid($chargeId);
                    }
                    // $this->dispatch('reset-payment');
                }
            }
        }
        $this->patientPaymentServices->UpdatePaymentChargesApplied($this->PATIENT_PAYMENT_ID);
        $this->showModal = false;
        $this->selectedCharges = [];
        $this->paymentAmounts = [];
        $this->dispatch('reload_philhealth_payment');
    }
    public function updatedSelectedCharges(bool $value, $id): bool
    {
        if (!$value) {
            $this->paymentAmounts[$id] = 0;
            return false;
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
        $balance = $this->serviceChargeServices->getItemBalance($id);

        if ($balance <= $newPay) {
            $mustPay = $balance;
        } else {
            $mustPay = $newPay;
        }
        $this->paymentAmounts[$id] = $mustPay;
        return true;
    }
    public bool $SelectAllCharges = false;
    public function UpdatedSelectAllCharges($value)
    {
        if ($value) {

            foreach ($this->dataList as $list) {

                $this->selectedCharges[$list->ID] = true;
                $isDone =    $this->updatedSelectedCharges(true, $list->ID);

                if ($isDone == false) {

                    return;
                }
            }
        } else {
            foreach ($this->dataList as $list) {
                $this->selectedCharges[$list->ID] = false;
                $this->paymentAmounts[$list->ID] = 0;
            }
        }
    }
    public function render()
    {
        if ($this->showModal) {
            $this->dataList =  $this->serviceChargeServices->getServiceChargeList_PH_Date($this->PATIENT_PAYMENT_ID, $this->PATIENT_ID, $this->LOCATION_ID, $this->DATE_FROM, $this->DATE_TO);
        } else {
            $this->dataList = [];
        }


        return view('livewire.phil-health.services-charges-modal');
    }
}
