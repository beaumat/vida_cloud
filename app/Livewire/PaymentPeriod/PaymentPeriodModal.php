<?php

namespace App\Livewire\PaymentPeriod;

use App\Services\AccountServices;
use App\Services\PaymentPeriodServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentPeriodModal extends Component
{
    #[Reactive]
    public $LOCATION_ID;

    private $paymentPeriodServices;
    private $accountServices;
    public string $RECEIPT_NO;
    public string $DATE_FROM;
    public string $DATE_TO;
    public float $TOTAL_PAYMENT;
    public float $TOTAL_WTAX;
    public int $BANK_ACCOUNT_ID;
    public string $DATE;

    public $accountList = [];
    public bool $showModal = false;
    public function boot(PaymentPeriodServices $paymentPeriodServices, AccountServices $accountServices)
    {
        $this->paymentPeriodServices = $paymentPeriodServices;
        $this->accountServices = $accountServices;
    }
    public function openModal()
    {
        $this->accountList = $this->accountServices->getBankAccount();
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save()
    {
        $this->validate(
            [
                'RECEIPT_NO' => 'required|string|unique:payment_period,receipt_no',
                'DATE' => 'required|date',
                'DATE_FROM' => 'required|date',
                'DATE_TO' => 'required|date',
                'LOCATION_ID' => 'required|numeric|exists:location,id',
                'TOTAL_PAYMENT' => 'required|numeric',
                // 'TOTAL_WTAX'        => 'required|numeric',
                'BANK_ACCOUNT_ID' => 'required|numeric|exists:account,id'
            ],
            [],
            [
                'RECEIPT_NO' => 'OR No.',
                'DATE' => 'OR Date',
                'DATE_FROM' => 'Date From',
                'DATE_TO' => 'Date To',
                'LOCATION_ID' => 'Location',
                'TOTAL_PAYMENT' => 'Total Payment',
                // 'TOTAL_WTAX'        => 'Total Wtax',
                'BANK_ACCOUNT_ID' => 'Bank Account'

            ]
        );

        DB::beginTransaction();
        try {

            $this->paymentPeriodServices->Store(
                $this->RECEIPT_NO,
                $this->LOCATION_ID,
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->TOTAL_PAYMENT,
                0,
                $this->BANK_ACCOUNT_ID,
                $this->DATE
            );
            DB::commit();
            $this->closeModal();
            $this->dispatch('period-refresh');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
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
        return view('livewire.payment-period.payment-period-modal');
    }
}
