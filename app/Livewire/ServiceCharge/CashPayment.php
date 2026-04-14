<?php
namespace App\Livewire\ServiceCharge;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CashPayment extends Component
{

    #[Reactive]
    public int $SERVICE_CHARGES_ID;
    public int $SERVICE_CHARGES_ITEM_ID;
    public float $AMOUNT;
    public float $ITEM_AMOUNT;
    public string $RECEIPT_REF_NO;
    public string $DATE;
    public bool $showModal = false;
    private $serviceChargeServices;
    private $patientPaymentServices;
    private $userServices;
    public function boot(
        ServiceChargeServices $serviceChargeServices,
        PatientPaymentServices $patientPaymentServices,
        UserServices $userServices
    ) {
        $this->serviceChargeServices  = $serviceChargeServices;
        $this->patientPaymentServices = $patientPaymentServices;
        $this->userServices           = $userServices;
    }
    #[On('cash-payment-prompt')]
    public function openModal($itemdata)
    {
        $this->SERVICE_CHARGES_ITEM_ID = (int) $itemdata['SERVICE_CHARGES_ITEM_ID'];
        $this->serviceChargeServices->updateServiceChargesItemPaid($this->SERVICE_CHARGES_ITEM_ID);
        $this->ITEM_AMOUNT = (float) $itemdata['SERVICE_CHARGES_ITEM_AMOUNT'];
        $this->AMOUNT      = $this->ITEM_AMOUNT;
        $this->DATE        = $this->userServices->getTransactionDateDefault();
        $this->showModal   = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save()
    {
        $this->validate(
            [
                'AMOUNT'         => 'required|numeric|not_in:0',
                'RECEIPT_REF_NO' => 'required',
            ],
            [],
            [
                'AMOUNT'         => 'Payment Amount',
                'RECEIPT_REF_NO' => 'SL No.',
            ]
        );

        if ($this->AMOUNT > $this->ITEM_AMOUNT) {
            session()->flash('error', 'Invalid Amount.');
            return;
        }

        if ($this->serviceChargeServices->getItemBalance($this->SERVICE_CHARGES_ITEM_ID) <= 0) {
            session()->flash('error', 'Invalid this item already paid');
            return;
        }

        $PATIENT_PAYMENT_ID = 0;
        $data               = $this->serviceChargeServices->get($this->SERVICE_CHARGES_ID);
        if ($data) {
            DB::beginTransaction();
            try {
                $PATIENT_PAYMENT_ID = (int) $this->patientPaymentServices->Store("", $this->DATE, $data->PATIENT_ID, $data->LOCATION_ID, $this->AMOUNT, $this->AMOUNT, 1, "", null, $this->RECEIPT_REF_NO, null, "", 1, 0, 0, 4);
                $PC_ID              = (int) $this->patientPaymentServices->PaymentChargeStore($PATIENT_PAYMENT_ID, $this->SERVICE_CHARGES_ITEM_ID, 0, $this->AMOUNT, 0, 4);
                $this->patientPaymentServices->PaymentChargesUpdate($PC_ID, $PATIENT_PAYMENT_ID, $this->SERVICE_CHARGES_ITEM_ID, 0, $this->AMOUNT);
                $this->serviceChargeServices->updateServiceChargesItemPaid($this->SERVICE_CHARGES_ITEM_ID);
                DB::commit();
                $getResult            = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
                $this->AMOUNT         = 0;
                $this->RECEIPT_REF_NO = '';
                $this->dispatch('update-amount', result: $getResult);
                $this->dispatch('update-status');
                $this->closeModal();
            } catch (\Throwable $th) {
                DB::rollBack();
                session()->flash('error', $th->getMessage());
            }
        }
    }
    public function render()
    {
        return view('livewire.service-charge.cash-payment');
    }
}
