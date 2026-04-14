<?php
namespace App\Livewire\ServiceCharge;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentAvailable extends Component
{

    #[Reactive]
    public int $SERVICE_CHARGES_ID;
    public int $SERVICE_CHARGES_ITEM_ID;
    public $paymentAmounts = [];
    public int $PATIENT_ID;
    public float $SERVICE_CHARGES_ITEM_AMOUNT;
    public float $GOT_APPLIED;
    public $showModal = false;
    private $patientPaymentServices;
    public $dataList = [];
    public float $PAY_AVAILABLE;
    public bool $gotInsert = false;
    private $serviceChargeServices;
    public function boot(PatientPaymentServices $patientPaymentServices, ServiceChargeServices $serviceChargeServices)
    {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->serviceChargeServices  = $serviceChargeServices;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function AddPayment(int $PATIENT_PAYMENT_ID)
    {

        $AMOUNT_APPLIED = (float) $this->paymentAmounts[$PATIENT_PAYMENT_ID];

        if ($AMOUNT_APPLIED <= 0) {

            session()->flash('error', 'Invalid Payment initial.');
            return;
        }
        // if got balance

        $payData = $this->patientPaymentServices->get($PATIENT_PAYMENT_ID);

        if ($payData) {
            $balance = $payData->AMOUNT ?? 0 - $payData->AMOUNT_APPLIED ?? 0;
            if ($balance < $AMOUNT_APPLIED) {
                session()->flash('error', 'The remaining balance is too low.');
                return;
            }

            try {
                DB::beginTransaction();
                $this->patientPaymentServices->PaymentChargeStore($PATIENT_PAYMENT_ID, $this->SERVICE_CHARGES_ITEM_ID, 0, $AMOUNT_APPLIED, 0, 0);
                DB::commit();
                $this->patientPaymentServices->UpdatePaymentChargesApplied($PATIENT_PAYMENT_ID);
                $this->serviceChargeServices->updateServiceChargesItemPaid($this->SERVICE_CHARGES_ITEM_ID);
                $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
                $this->dispatch('update-amount', result: $getResult);
                $this->closeModal();
            } catch (\Throwable $th) {
                DB::rollBack();
                session()->flash('error', $th->getMessage());
            }
        }
    }

    #[On('payment-avaliable-prompt')]
    public function OpenList($itemdata)
    {
        $this->gotInsert = false;
        $this->reset('paymentAmounts');
        $this->SERVICE_CHARGES_ITEM_ID = (int) $itemdata['SERVICE_CHARGES_ITEM_ID'];
        $this->serviceChargeServices->updateServiceChargesItemPaid($this->SERVICE_CHARGES_ITEM_ID);
        $this->SERVICE_CHARGES_ITEM_AMOUNT = (float) $itemdata['SERVICE_CHARGES_ITEM_AMOUNT'];
        $this->GOT_APPLIED                 = (float) $this->patientPaymentServices->GetPaymentRemainingItem($this->SERVICE_CHARGES_ITEM_ID);
        $data                              = $this->serviceChargeServices->get($this->SERVICE_CHARGES_ID);
        $this->dataList                    = $this->patientPaymentServices->PaymentAvailableList_SC($data->PATIENT_ID, $data->LOCATION_ID, $this->SERVICE_CHARGES_ITEM_ID);

        $this->showModal = true;
        foreach ($this->dataList as $list) {
            if ($list->IS_COUNT == 0) {

                $AMOUNT                          = (float) $list->AMOUNT ?? 0;
                $AMOUNT_APPLIED                  = (float) $list->AMOUNT_APPLIED ?? 0;
                $BALANCE                         = (float) $AMOUNT - $AMOUNT_APPLIED;
                $RECOMMENDED                     = (float) $this->SERVICE_CHARGES_ITEM_AMOUNT - $this->GOT_APPLIED;
                $PAY_AVAILABLE                   = (float) $BALANCE > $RECOMMENDED ? $RECOMMENDED : $BALANCE;
                $this->paymentAmounts[$list->ID] = (float) $PAY_AVAILABLE;
                return;
            }
        }
    }

    public function render()
    {
        return view('livewire.service-charge.payment-available');
    }
}
