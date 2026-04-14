<?php
namespace App\Livewire\Payment;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payments')]
class PaymentForm extends Component
{
    public int $ID;
    public string $CODE;
    public $DATE;
    public int $CUSTOMER_ID;
    public int $LOCATION_ID;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public int $PAYMENT_METHOD_ID;
    public string $CARD_NO;
    public $CARD_EXPIRY_DATE;
    public string $RECEIPT_REF_NO;
    public $RECEIPT_DATE;
    public string $NOTES;
    public int $UNDEPOSITED_FUNDS_ACCOUNT_ID;
    public int $OVERPAYMENT_ACCOUNT_ID;
    public int $STATUS;
    public string $STATUS_DATE;
    public string $STATUS_DESCRIPTION;
    public bool $BANK_MODE = true;
    public bool $DEPOSITED;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public bool $UNPOSTED     = true;
    public $locationList      = [];
    public $contactList       = [];
    public $paymentMethodList = [];
    public $accountList       = [];
    private $paymentServices;
    private $locationServices;
    private $userServices;
    private $accountServices;
    private $paymentMethodServices;
    private $contactServices;
    public bool $Modify             = true;
    public bool $showCardNo         = false;
    public bool $showCardDateExpire = false;
    public bool $showReceiptNo      = false;
    public bool $showReceiptDate    = false;
    public bool $showFileName       = false;
    public string $TITLE_REF;
    public string $TITLE_DATE;

    private $systemSettingServices;

    private $accountJournalServices;
    private $documentStatusServices;
    public function boot(
        PaymentServices $paymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountServices $accountServices,
        PaymentMethodServices $paymentMethodServices,
        ContactServices $contactServices,
        AccountJournalServices $accountJournalServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices
    ) {
        $this->paymentServices        = $paymentServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->accountServices        = $accountServices;
        $this->paymentMethodServices  = $paymentMethodServices;
        $this->contactServices        = $contactServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices  = $systemSettingServices;
    }
    public function getInfo($data)
    {
        $this->ID                           = $data->ID;
        $this->DATE                         = $data->DATE;
        $this->CODE                         = $data->CODE;
        $this->CUSTOMER_ID                  = $data->CUSTOMER_ID;
        $this->LOCATION_ID                  = $data->LOCATION_ID;
        $this->AMOUNT                       = $data->AMOUNT;
        $this->AMOUNT_APPLIED               = $data->AMOUNT_APPLIED;
        $this->PAYMENT_METHOD_ID            = $data->PAYMENT_METHOD_ID;
        $this->CARD_NO                      = $data->CARD_NO ?? null;
        $this->CARD_EXPIRY_DATE             = $data->CARD_EXPIRY_DATE ?? null;
        $this->RECEIPT_REF_NO               = $data->RECEIPT_REF_NO ?? null;
        $this->RECEIPT_DATE                 = $data->RECEIPT_DATE ?? null;
        $this->NOTES                        = $data->NOTES ?? null;
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $data->UNDEPOSITED_FUNDS_ACCOUNT_ID ?? 0;
        $this->OVERPAYMENT_ACCOUNT_ID       = $data->OVERPAYMENT_ACCOUNT_ID ?? 0;
        $this->ACCOUNTS_RECEIVABLE_ID       = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;
        $this->STATUS                       = $data->STATUS ?? 0;
        if($this->STATUS == 16) {
            $this->removeJournal();
        }
        $this->STATUS_DESCRIPTION           = $this->documentStatusServices->getDesc($this->STATUS);
        $this->STATUS_DATE                  = $data->STATUS_DATE ?? null;
        $this->DEPOSITED                    = $data->DEPOSITED ?? null;
        $this->updatedpaymentmethodid();
        $this->Modify = false;
    }

    #[On('reset-payment')]
    public function ResetPaymentApplied()
    {
        $this->AMOUNT_APPLIED = (float) $this->paymentServices->UpdatePaymentApplied($this->ID);
    }
    private function LoadDropDown()
    {
        if ($this->BANK_MODE) {
            $this->accountList = $this->accountServices->getBankAccount();
        }

        $this->contactList       = $this->contactServices->getCustoPatientList();
        $this->locationList      = $this->locationServices->getList();
        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->paymentServices->get($id);
            if ($data) {
                $this->LoadDropDown();
                $this->getInfo($data);
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('customerspayment')->with('error', $errorMessage);
        }
        $this->LoadDropDown();
        $this->ID                           = 0;
        $this->DATE                         = $this->userServices->getTransactionDateDefault();
        $this->CODE                         = '';
        $this->CUSTOMER_ID                  = 0;
        $this->LOCATION_ID                  = $this->userServices->getLocationDefault();
        $this->AMOUNT                       = 0;
        $this->AMOUNT_APPLIED               = 0;
        $this->PAYMENT_METHOD_ID            = 0;
        $this->CARD_NO                      = '';
        $this->CARD_EXPIRY_DATE             = null;
        $this->RECEIPT_REF_NO               = '';
        $this->RECEIPT_DATE                 = null;
        $this->NOTES                        = '';
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $this->BANK_MODE ? 0 : $this->accountServices->getByName('Undeposited Funds');
        $this->OVERPAYMENT_ACCOUNT_ID       = 0;
        $this->ACCOUNTS_RECEIVABLE_ID       = (int) $this->accountServices->getByName('Accounts Receivables');
        $this->STATUS                       = 0;
        $this->STATUS_DESCRIPTION           = $this->documentStatusServices->getDesc($this->STATUS);
        $this->DEPOSITED                    = $this->BANK_MODE ? true : false;
        $this->Modify                       = true;
        $this->updatedpaymentmethodid();
    }

    public function save()
    {

        $this->validate(
            [
                'CUSTOMER_ID'                  => 'required|integer|exists:contact,id',
                'CODE'                         => $this->ID > 0 ? 'required|unique:payment,code,' . $this->ID : 'nullable',
                'PAYMENT_METHOD_ID'            => 'required|integer|exists:payment_method,id',
                'DATE'                         => 'required|date',
                'LOCATION_ID'                  => 'required|integer|exists:location,id',
                'AMOUNT'                       => 'required|not_in:0',
                'ACCOUNTS_RECEIVABLE_ID'       => 'required|integer|exists:account,id',
                'UNDEPOSITED_FUNDS_ACCOUNT_ID' => 'required|integer|exists:account,id',
            ],
            [],
            [
                'CUSTOMER_ID'                  => 'Customer',
                'CODE'                         => 'Reference No.',
                'PAYMENT_METHOD_ID'            => 'Payment Method',
                'DATE'                         => 'Date',
                'LOCATION_ID'                  => 'Location',
                'AMOUNT'                       => 'Amount',
                'ACCOUNTS_RECEIVABLE_ID'       => 'Accounts Receivable',
                'UNDEPOSITED_FUNDS_ACCOUNT_ID' => 'Deposit to Bank Account',
            ]
        );
        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        DB::beginTransaction();
        try {

            if ($this->ID == 0) {

                $this->ID = $this->paymentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    0,
                    $this->PAYMENT_METHOD_ID,
                    $this->CARD_NO,
                    $this->CARD_EXPIRY_DATE,
                    $this->RECEIPT_REF_NO,
                    $this->RECEIPT_DATE,
                    $this->NOTES,
                    $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                    $this->OVERPAYMENT_ACCOUNT_ID,
                    $this->DEPOSITED,
                    $this->ACCOUNTS_RECEIVABLE_ID
                );

                DB::commit();

                return Redirect::route('customerspayment_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $data = $this->paymentServices->Get($this->ID);
                if ($data) {
                    if ($this->STATUS == 16) {
                        $JNO = $this->accountJournalServices->getRecord($this->paymentServices->object_type_payment, $this->ID);
                        if ($JNO > 0) {
                            // UNDEPOSITED_FUNDS_ACCOUNT_ID on CREDIT
                            $this->accountJournalServices->AccountSwitch(
                                $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                                $data->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->CUSTOMER_ID,
                                $this->ID,
                                $this->paymentServices->object_type_payment,
                                $this->DATE,
                                0
                            );
                        }
                    }

                    $this->paymentServices->Update($this->ID, $this->CODE, $this->DATE, $this->CUSTOMER_ID, $this->LOCATION_ID, $this->AMOUNT, $this->PAYMENT_METHOD_ID, $this->CARD_NO, $this->CARD_EXPIRY_DATE, $this->RECEIPT_REF_NO, $this->RECEIPT_DATE, $this->NOTES, $this->UNDEPOSITED_FUNDS_ACCOUNT_ID, $this->OVERPAYMENT_ACCOUNT_ID, $this->DEPOSITED, $this->ACCOUNTS_RECEIVABLE_ID);

                    DB::commit();
                }

                $this->Modify = false;
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updateCancel()
    {
        $data = $this->paymentServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
        $this->Modify = false;
    }
    public function updatedpaymentmethodid()
    {
        $paymentMethod = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);

        if ($paymentMethod) {
            $data                     = $this->paymentMethodServices->PaymentMethodSwitch($paymentMethod->PAYMENT_TYPE);
            $this->showCardNo         = (bool) $data['showCardNo'];
            $this->showCardDateExpire = (bool) $data['showCardDateExpire'];
            $this->showReceiptNo      = (bool) $data['showReceiptNo'];
            $this->showReceiptDate    = (bool) $data['showReceiptDate'];
            $this->showFileName       = (bool) $data['showFileName'];
            $this->TITLE_REF          = (string) $data['titleRef'];
            $this->TITLE_DATE         = (string) $data['titleDate'];
            // $this->showTax = (bool) $data['showTax'];

            return;
        }

        $this->showCardNo         = false;
        $this->showCardDateExpire = false;
        $this->showReceiptNo      = false;
        $this->showReceiptDate    = false;
        $this->showFileName       = false;
    }

    public function getPosted()
    {
        try {

            DB::beginTransaction();

            $payment           = $this->paymentServices->object_type_payment;
            $paymentInvoicesId = $this->paymentServices->object_type_payment_invoices;

            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($payment, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($payment, $this->ID) + 1;
            }

            $paymentData = $this->paymentServices->PaymentJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $paymentData, $this->LOCATION_ID, $payment, $this->DATE, "UF");
            $paymentDataR = $this->paymentServices->PaymentJournalRemaining($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $paymentDataR, $this->LOCATION_ID, $payment, $this->DATE, "A/R");
            $paymentInvoiceData = $this->paymentServices->PaymentInvoicejournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $paymentInvoiceData, $this->LOCATION_ID, $paymentInvoicesId, $this->DATE, "A/R");

            $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                $this->paymentServices->StatusUpdate($this->ID, 15);
                DB::commit();
                $data = $this->paymentServices->get($this->ID);
                if ($data) {
                    $this->getInfo($data);
                    $this->Modify = false;
                    return;
                }
                session()->flash('message', 'Successfully posted');
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->paymentServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('customerspayment_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->paymentServices->object_type_payment, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->paymentServices->object_type_payment, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }

    public function render()
    {
        return view('livewire.payment.payment-form');
    }
}
