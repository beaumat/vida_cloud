<?php

namespace App\Livewire\PhilHealth;

use App\Services\InvoiceServices;
use App\Services\PatientPaymentServices;
use App\Services\PhilHealthServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentList extends Component
{
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $PHILHEALTH_ID;
    public $paymentList = [];
    public int $i = 0;
    public float $AMOUNT;
    public string $NOTES;
    public string $RECEIPT_REF_NO;
    public $RECEIPT_DATE;
    public float $P1_TOTAL = 0;
    public float $PAYMENT_AMOUNT = 0;
    public float $BALANCE = 0;
    private $patientPaymentServices;
    private $philHealthServices;
    private $invoiceServices;
    public function boot(
        PatientPaymentServices $patientPaymentServices,
        PhilHealthServices $philHealthServices,
        InvoiceServices $invoiceServices
    ) {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->philHealthServices = $philHealthServices;
        $this->invoiceServices = $invoiceServices;
    }
    public function mount()
    {
        $this->ClearInsert();
    }
    public function ClearInsert()
    {
        $this->RECEIPT_DATE = null;
        $this->RECEIPT_REF_NO = '';
        $this->NOTES =  '';
        $this->AMOUNT = 0;
    }
    public $editId = null;
    public string $editReceiptDate;
    public float $editAmount;
    public string $editReceiptRefNo;
    public string $editNotes;
    public function Store()
    {
        $this->validate(
            [
                'RECEIPT_REF_NO'    => 'required',
                'RECEIPT_DATE'      => 'required',
                'AMOUNT'            => 'required|not_in:0'
            ],
            [],
            [
                'RECEIPT_DATE'      => 'O.R Date',
                'RECEIPT_REF_NO'    => 'OR No.',
                'AMOUNT'            => 'Amount'
            ]
        );
        DB::beginTransaction();
        try {
            //code...
            $this->patientPaymentServices->PH_Store($this->PHILHEALTH_ID, $this->AMOUNT,  $this->RECEIPT_REF_NO, $this->RECEIPT_DATE, $this->NOTES);
            $TotalPay = (float) $this->patientPaymentServices->getPH_TotalPay($this->PHILHEALTH_ID);
            $this->philHealthServices->UpdatePayment($this->PHILHEALTH_ID, $TotalPay);
            $this->ClearInsert();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Philhealth payment store record: ' . $e->getMessage());
        }
    }
    public function Edit(int $ID)
    {
        $data = $this->patientPaymentServices->get($ID);
        if ($data) {
            $this->editId = $data->ID;
            $this->editAmount = $data->AMOUNT;
            $this->editReceiptRefNo = $data->RECEIPT_REF_NO ?? '';
            $this->editReceiptDate = $data->RECEIPT_DATE;
            $this->editNotes = $data->NOTES ?? '';
        }
    }
    public function Cancel()
    {
        $this->editId =  null;
        $this->editReceiptDate = '';
        $this->editAmount = 0;
        $this->editReceiptRefNo = '';
        $this->editNotes = '';
    }
    public function Update()
    {
        $this->validate(
            [
                'editReceiptRefNo' => 'required',
                'editReceiptDate' => 'required',
                'editAmount' => 'required|not_in:0'

            ],
            [],
            [
                'editReceiptRefNo' => 'OR No.',
                'editReceiptDate' => 'O.R Date',
                'editAmount' => 'Amount'

            ]
        );
        DB::beginTransaction();
        try {
            //code...
            $this->patientPaymentServices->PH_Update(
                $this->editId,
                $this->PHILHEALTH_ID,
                $this->editAmount,
                $this->editReceiptRefNo,
                $this->editReceiptDate,
                $this->editNotes
            );
            $TotalPay = (float) $this->patientPaymentServices->getPH_TotalPay($this->PHILHEALTH_ID);
            $this->philHealthServices->UpdatePayment($this->PHILHEALTH_ID, $TotalPay);
            $this->Cancel();
            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            Log::error('Philhealth payment update record: ' . $e->getMessage());
        }
    }
    public function Delete(int $ID)
    {
        DB::beginTransaction();
        try {
            $isDelete =  $this->patientPaymentServices->PH_Delete($ID, $this->PHILHEALTH_ID);
            if ($isDelete) {
                $TotalPay = (float) $this->patientPaymentServices->getPH_TotalPay($this->PHILHEALTH_ID);
                $this->philHealthServices->UpdatePayment($this->PHILHEALTH_ID, $TotalPay);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Philhealth payment delete record: ' . $e->getMessage());
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function getAmount()
    {
        $data = $this->philHealthServices->get($this->PHILHEALTH_ID);
        if ($data) {
            $this->P1_TOTAL = $data->P1_TOTAL ?? 0;
            $this->PAYMENT_AMOUNT = $data->PAYMENT_AMOUNT ?? 0;
            $this->BALANCE =   $this->P1_TOTAL -  $this->PAYMENT_AMOUNT;
        }
    }

    public function OpenPayShow(int $PATIENT_PAYMENT_ID)
    {
        $data = [
            'PATIENT_PAYMENT_ID' => $PATIENT_PAYMENT_ID
        ];
        $this->dispatch('open-payment-sc', result: $data);
    }
    #[On('reload_philhealth_payment')]
    public function render()
    {
        $this->getAmount();
        $this->paymentList = $this->patientPaymentServices->PH_List($this->PHILHEALTH_ID, $this->PATIENT_ID, $this->LOCATION_ID);
        return view('livewire.phil-health.payment-list');
    }
}
