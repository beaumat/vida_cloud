<?php

namespace App\Livewire\Invoice;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\InvoiceServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ReceivedPayment extends Component
{
    #[Reactive]
    public int $INVOICE_ID;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $ACCOUNTS_RECEIVABLE_ID;
    #[Reactive]
    public int $INVOICE_STATUS_ID;

    public int $openStatus = 0;
    public float $AMOUNT;
    public int $PAYMENT_METHOD_ID;
    public string $CARD_NO = "";
    public  $CARD_EXPIRY_DATE = null;
    public string $RECEIPT_REF_NO;
    public  $RECEIPT_DATE = null;
    public string $NOTES = '';
    public int $UNDEPOSITED_FUNDS_ACCOUNT_ID;
    public $dataList = [];
    public $paymentMethodList = [];
    public $accountList = [];
    private $paymentServices;
    private $userServices;
    private $invoiceServices;
    private $paymentMethodServices;
    private $accountServices;
    private $accountJournalServices;
    private $philHealthServices;
    public function boot(
        PaymentServices $paymentServices,
        UserServices $userServices,
        InvoiceServices $invoiceServices,
        PaymentMethodServices $paymentMethodServices,
        AccountServices $accountServices,
        AccountJournalServices $accountJournalServices,
        PhilHealthServices $philHealthServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->userServices = $userServices;
        $this->invoiceServices = $invoiceServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->accountServices = $accountServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->philHealthServices = $philHealthServices;
    }
    public function clearData()
    {
        $this->CARD_NO = "";
        $this->AMOUNT = 0;
        $this->RECEIPT_REF_NO = '';
        $this->CARD_EXPIRY_DATE = null;
        $this->NOTES = '';
        $this->PAYMENT_METHOD_ID = 0;
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
    }
    public function LoadList()
    {
        $this->accountList =  $this->accountServices->getBankAccount();
        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();
    }
    public function AddPayment()
    {


        $this->validate(
            [
                'INVOICE_ID'                    => 'required|integer|exists:invoice,id',
                'CUSTOMER_ID'                   => 'required|integer|exists:contact,id',
                'ACCOUNTS_RECEIVABLE_ID'        => 'required|integer|exists:account,id',
                'UNDEPOSITED_FUNDS_ACCOUNT_ID'  => 'required|integer|exists:account,id',
                'PAYMENT_METHOD_ID'             => 'required|integer|exists:payment_method,id',
                'AMOUNT'                        => 'required|not_in:0',
                'RECEIPT_REF_NO'                => 'required|string'
            ],
            [],
            [
                'INVOICE_ID'                    => 'Invoice',
                'CUSTOMER_ID'                   => 'Customer',
                'ACCOUNTS_RECEIVABLE_ID'        => 'Account Receivable',
                'UNDEPOSITED_FUNDS_ACCOUNT_ID'  => 'Bank Account',
                'PAYMENT_METHOD_ID'             => 'Payment Method',
                'AMOUNT'                        => 'Payment Amount',
                'RECEIPT_REF_NO'                => 'Reference No.'
            ]
        );



        if ($this->paymentServices->PaymenIsOver($this->INVOICE_ID, $this->AMOUNT) == true) {
            session()->flash('error', 'The payment exceeds the available balance');
            return;
        }

        DB::beginTransaction();
        try {
            $ID = $this->paymentServices->Store(
                "",
                $this->userServices->getTransactionDateDefault(),
                $this->CUSTOMER_ID,
                $this->LOCATION_ID,
                $this->AMOUNT,
                $this->AMOUNT,
                $this->PAYMENT_METHOD_ID,
                $this->CARD_NO,
                $this->CARD_EXPIRY_DATE,
                $this->RECEIPT_REF_NO,
                $this->RECEIPT_DATE,
                $this->NOTES ?? '',
                $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                0,
                false,
                $this->ACCOUNTS_RECEIVABLE_ID,
                0
            );


            $this->paymentServices->PaymentInvoiceStore(
                $ID,
                $this->INVOICE_ID,
                0,
                $this->AMOUNT,
                0,
                $this->ACCOUNTS_RECEIVABLE_ID
            );
            $this->invoiceServices->updateInvoiceBalance($this->INVOICE_ID);
            $isGood = $this->paymentServices->getPosted($ID, $this->userServices->getTransactionDateDefault(), $this->LOCATION_ID);
            if ($isGood) {
                $PHILHEALTH_ID =  $this->philHealthServices->Get_ID_by_INVOICE_ID($this->INVOICE_ID);
                if ($PHILHEALTH_ID > 0) {
                    $this->philHealthServices->makePayableForDoctor($PHILHEALTH_ID, $this->LOCATION_ID, $this->userServices->getTransactionDateDefault());
                }
                DB::commit();
                $getResult = $this->invoiceServices->ReComputed($this->INVOICE_ID);
                $this->dispatch('update-amount', result: $getResult);
                $this->clearData();
            } else {
                DB::rollBack();
                return;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function DeletePayment(int $ID)
    {

        $data = $this->paymentServices->getPaymentInvoiceDetails($ID);
        if ($data) {
            $this->paymentServices->PaymentInvoiceDelete(
                $this->ID,
                $data->PAYMENT_ID,
                $this->INVOICE_ID
            );
        }
    }

    public function PaymentList()
    {
        $this->dataList =  $this->paymentServices->InvoicePaymentList($this->INVOICE_ID, $this->CUSTOMER_ID);
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
        $this->LoadList();
        $this->PaymentList();

        return view('livewire.invoice.received-payment');
    }
}
