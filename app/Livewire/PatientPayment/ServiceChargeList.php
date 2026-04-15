<?php

namespace App\Livewire\PatientPayment;

use App\Services\PatientPaymentServices;
use App\Services\PhilHealthServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ServiceChargeList extends Component
{
    public $showModal = false;
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $PATIENT_PAYMENT_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    public $dataList = [];
    public $selectedCharges = [];
    public $paymentAmounts = [];
    private $serviceChargeServices;
    private $patientPaymentServices;
    private $philHealthServices;
    public bool $isDisabled = false;

    public $PHILHEALTH_ID = 0;
    public string $DT_FROM;
    public string $DT_TO;


    public function boot(ServiceChargeServices $serviceChargeServices, PatientPaymentServices $patientPaymentServices, PhilHealthServices $philHealthServices)
    {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->patientPaymentServices = $patientPaymentServices;
        $this->philHealthServices  = $philHealthServices;
    }
    public function mount(int $PATIENT_ID, int $LOCATION_ID, int $PATIENT_PAYMENT_ID, float $AMOUNT, float $AMOUNT_APPLIED, int $PHILHEALTH_ID = 0)
    {
        $this->PATIENT_ID = $PATIENT_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->PATIENT_PAYMENT_ID = $PATIENT_PAYMENT_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
        $this->PHILHEALTH_ID = $PHILHEALTH_ID;
        if ($PHILHEALTH_ID > 0) {
            $data = $this->philHealthServices->get($PHILHEALTH_ID);

            if ($data) {
                $this->DT_FROM = $data->DATE_ADMITTED;
                $this->DT_TO = $data->DATE_DISCHARGED;
            }
        }
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
        $balance = $this->serviceChargeServices->getItemBalance($id);

        if ($balance <= $newPay) {
            $mustPay = $balance;
        } else {
            $mustPay = $newPay;
        }
        $this->paymentAmounts[$id] = $mustPay;
    }
    public function openModal()
    {
        $this->isDisabled = $this->CheckingHavePaymentNotUsed();
        $this->showModal = true;
    }
    public function closeModal()
    {

        $this->showModal = false;
    }
    private function CheckingHavePaymentNotUsed(): bool
    {
        return false;
    }

    public function save(): void
    {
        $creditBalance = (float) $this->AMOUNT - $this->AMOUNT_APPLIED;
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
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }

        if ($CollectAmount == 0) {
            session()->flash('error', 'payment selected not found.');
            return;
        }
        if ($creditBalance < $CollectAmount) {
            session()->flash('error', 'you dont have credit balance.');
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
                    $ID = (int) $this->patientPaymentServices->PaymentChargesExist(
                        $this->PATIENT_PAYMENT_ID,
                        $chargeId
                    );
                    if ($ID > 0) {
                        $this->patientPaymentServices->PaymentChargesUpdate(
                            $ID,
                            $this->PATIENT_PAYMENT_ID,
                            $chargeId,
                            0,
                            $chargeAmount
                        );
                    } else {
                        $this->patientPaymentServices->PaymentChargeStore(
                            $this->PATIENT_PAYMENT_ID,
                            $chargeId,
                            0,
                            $chargeAmount,
                            0,
                            0
                        );
                    }

                    $this->serviceChargeServices->updateServiceChargesItemPaid($chargeId);
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

        if ($this->PHILHEALTH_ID > 0) {
            $this->dataList = $this->serviceChargeServices->getServiceChargeList_PH_Date($this->PATIENT_PAYMENT_ID, $this->PATIENT_ID, $this->LOCATION_ID, $this->DT_FROM, $this->DT_TO);
        } else {
            $this->dataList = $this->serviceChargeServices->getServiceChargeList_NotPhilhealth($this->PATIENT_PAYMENT_ID, $this->PATIENT_ID, $this->LOCATION_ID);
        }

        return view('livewire.patient-payment.service-charge-list');
    }
}
