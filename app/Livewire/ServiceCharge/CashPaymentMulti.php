<?php
namespace App\Livewire\ServiceCharge;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CashPaymentMulti extends Component
{

    #[Reactive]
    public int $SERVICE_CHARGES_ID;
    public float $AMOUNT;
    public string $RECEIPT_REF_NO;
    public string $DATE;
    public $itemList = [];
    public $itemSelected = [];
    public bool $SelectAll = false;
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
    #[On('cash-payment-prompt-multi')]
    public function openModal()
    {

        $this->SelectAll = false;
        $this->reset('itemSelected');

        $this->itemList  = $this->serviceChargeServices->ItemView($this->SERVICE_CHARGES_ID, true);
        $this->DATE      = $this->userServices->getTransactionDateDefault();
        $this->showModal = true;
    }
    public function updateditemSelected()
    {
        $this->Recomputed();
    }
    private function CheckIsItemSelected(): bool
    {

        foreach ($this->itemSelected as $id => $isSelected) {
            if ($isSelected) {
                return true;
            }
        }
        return false;
    }
    private function Recomputed()
    {
        $arrayItem = [];
        foreach ($this->itemSelected as $id => $isSelected) {
            if ($isSelected) {
                $arrayItem[] = $id;
            }
        }
        $this->AMOUNT = $this->serviceChargeServices->getItemListSum($this->SERVICE_CHARGES_ID, $arrayItem);
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            foreach ($this->itemList as $list) {
                $this->itemSelected[$list->ID] = true;
            }
        } else {
            $this->reset('itemSelected');
        }

        $this->Recomputed();
    }
    public function clearForm()
    {
        $this->reset('itemSelected');
        $this->AMOUNT = 0;
    }
    public function closeModal()
    {

        $this->clearForm();
        $this->showModal = false;
    }
    public function save()
    {
        $this->validate(
            [
                'AMOUNT'         => 'required|not_in:0',
                'RECEIPT_REF_NO' => 'required',
            ],
            [],
            [
                'AMOUNT'         => 'Payment Amount',
                'RECEIPT_REF_NO' => 'SL No.',
            ]
        );

        if (! $this->CheckIsItemSelected()) {
            session()->flash('error', 'Invalid item not selected');
            return;
        }

        // if ($this->serviceChargeServices->getItemBalance($this->SERVICE_CHARGES_ITEM_ID) <= 0) {
        //     session()->flash('error', 'Invalid this item already paid');
        //     return;
        // }

        $PATIENT_PAYMENT_ID = 0;

        $data = $this->serviceChargeServices->get($this->SERVICE_CHARGES_ID);
        if ($data) {
            DB::beginTransaction();
            try {
                $PATIENT_PAYMENT_ID = $this->patientPaymentServices->Store(
                    "",
                    $this->DATE,
                    $data->PATIENT_ID,
                    $data->LOCATION_ID,
                    $this->AMOUNT,
                    $this->AMOUNT,
                    1,
                    "",
                    null,
                    $this->RECEIPT_REF_NO,
                    null,
                    "",
                    1,
                    0,
                    0,
                    4
                );
                $this->itemSelectStore($PATIENT_PAYMENT_ID);

                // DATA
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
    private function itemSelectStore(int $PATIENT_PAYMENT_ID)
    {
        foreach ($this->itemSelected as $id => $isSelected) {
            if ($isSelected) {
                $AMOUNT = (float) $this->serviceChargeServices->getItemSum($this->SERVICE_CHARGES_ID, $id);
                $this->itemDataStore($PATIENT_PAYMENT_ID, $id, $AMOUNT);
            }
        }
    }
    private function itemDataStore(int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID, float $AMOUNT)
    {
        $PC_ID = (int) $this->patientPaymentServices->PaymentChargeStore(
            $PATIENT_PAYMENT_ID,
            $SERVICE_CHARGES_ITEM_ID,
            0,
            $AMOUNT,
            0,
            4
        );

        $this->patientPaymentServices->PaymentChargesUpdate(
            $PC_ID,
            $PATIENT_PAYMENT_ID,
            $SERVICE_CHARGES_ITEM_ID,
            0,
            $AMOUNT
        );

        $this->serviceChargeServices->updateServiceChargesItemPaid($SERVICE_CHARGES_ITEM_ID);
    }
    public function render()
    {
        if ($this->showModal) {
            $this->itemList = $this->serviceChargeServices->ItemView($this->SERVICE_CHARGES_ID, true);
        }

        return view('livewire.service-charge.cash-payment-multi');
    }
}
