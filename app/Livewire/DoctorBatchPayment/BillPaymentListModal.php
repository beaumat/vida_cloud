<?php

namespace App\Livewire\DoctorBatchPayment;

use App\Services\BillPaymentServices;
use App\Services\DoctorBatchServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillPaymentListModal extends Component
{

    #[Reactive]
    public int $DOCTOR_BATCH_ID;
    public int $DOCTOR_ID;
    public int $LOCATION_ID;
    public $dataList = [];
    public bool $showModal = false;
    private $doctorBatchServices;

    private $billPaymentServices;
    public function boot(DoctorBatchServices $doctorBatchServices, BillPaymentServices $billPaymentServices)
    {
        $this->doctorBatchServices = $doctorBatchServices;
        $this->billPaymentServices = $billPaymentServices;
    }
    public function openModal()
    {
        $dataInfo = $this->doctorBatchServices->Get($this->DOCTOR_BATCH_ID);
        if ($dataInfo) {
            $this->DOCTOR_ID = $dataInfo->DOCTOR_ID;
            $this->LOCATION_ID = $dataInfo->LOCATION_ID;
            $this->getData();
            $this->showModal = true;
        }
    }
    public function getData() {
                    $this->dataList = $this->billPaymentServices->getDoctorPaidList($this->DOCTOR_ID, $this->LOCATION_ID);
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function addItem(int $CHECK_ID, int $PF_PERIOD_ID)
    {
        DB::beginTransaction();
        try {
            $this->doctorBatchServices->StorePaid(
                $this->DOCTOR_BATCH_ID,
                $PF_PERIOD_ID,
                $CHECK_ID
            );
            DB::commit();
            $this->getData();
            $this->dispatch('refresh-list-doctor-batch');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.doctor-batch-payment.bill-payment-list-modal');
    }
}
