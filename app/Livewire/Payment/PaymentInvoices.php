<?php

namespace App\Livewire\Payment;

use App\Services\AccountJournalServices;
use App\Services\InvoiceServices;
use App\Services\PaymentServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentInvoices extends Component
{
    #[Reactive]
    public int $PAYMENT_ID;
    public $dataList = [];
    #[Reactive]
    public int $STATUS;
    public int $openStatus;
    private $paymentServices;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    public float $prevAmount;
    public float $orgAmount;
    public $editPaymentId = null;
    public int $editInvoiceId;
    public float $editAmountApplied;
    private $invoiceServices;
    private $accountJournalServices;
    public function boot(
        PaymentServices $paymentServices,
        InvoiceServices $invoiceServices,
        AccountJournalServices   $accountJournalServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->invoiceServices = $invoiceServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function edit(int $ID, int $INVOICE_ID, float $Applied)
    {
        $this->editPaymentId = $ID;
        $this->editInvoiceId = $INVOICE_ID;
        $this->editAmountApplied = $Applied;
        $this->prevAmount = $Applied;
        $data = $this->invoiceServices->get($INVOICE_ID);
        if ($data) {
            $this->orgAmount = $data->AMOUNT;
        }
    }

    public function cancel()
    {
        $this->editPaymentId = null;
    }
    public function update()
    {
        $RemainAmount = (float) $this->AMOUNT_APPLIED - $this->prevAmount;
        if ($this->AMOUNT < ($RemainAmount + $this->editAmountApplied)) {
            session()->flash('error', 'Invalid payment initial. the remaining payment to low.');
            return;
        }

        $totalPay = (float) $this->paymentServices->getTotalPay(
            $this->editInvoiceId,
            $this->PAYMENT_ID
        );
        $current_balance = (float) $this->orgAmount - $totalPay;
        if ($current_balance < $this->editAmountApplied) {
            session()->flash('error', 'Invalid payment initial is to high from invoice balance. please enter exactly initial amount');
            return;
        }
        $this->paymentServices->PaymentInvoiceUpdate($this->editPaymentId, $this->PAYMENT_ID, $this->editInvoiceId, 0, $this->editAmountApplied);
        $this->invoiceServices->updateInvoiceBalance($this->editInvoiceId);
        $this->editPaymentId = null;
        $this->dispatch('reset-payment');
    }
    public function delete(int $ID, int $INVOICE_ID)
    {

        DB::beginTransaction();
        try {

            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->paymentServices->object_type_payment,
                    $this->PAYMENT_ID
                );
                $payData = $this->paymentServices->get($this->PAYMENT_ID);
                if ($payData) {
                    $payInvoices = $this->paymentServices->PaymentInvoiceGet($ID, $this->PAYMENT_ID, $INVOICE_ID);
                    if ($payInvoices) {
                        // ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $payInvoices->ACCOUNTS_RECEIVABLE_ID,
                            $payData->LOCATION_ID,
                            $JOURNAL_NO,
                            $INVOICE_ID,
                            $ID,
                            $this->paymentServices->object_type_payment_invoices,
                            $payData->DATE,
                            1
                        );
                    }
                }
            }


            $this->paymentServices->PaymentInvoiceDelete($ID, $this->PAYMENT_ID, $INVOICE_ID);
            $this->invoiceServices->updateInvoiceBalance($INVOICE_ID);
            $this->dispatch('reset-payment');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(int $PAYMENT_ID, int $CUSTOMER_ID, int $LOCATION_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->PAYMENT_ID = $PAYMENT_ID;
        $this->CUSTOMER_ID = $CUSTOMER_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
    }
    #[On('reload_payment_invoice')]
    public function render()
    {
        $this->dataList = $this->paymentServices->PaymentInvoiceList($this->PAYMENT_ID);
        return view('livewire.payment.payment-invoices',);
    }
}
