<?php
namespace App\Livewire\BillPayments;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BillPaymentServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\PaymentPeriodServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use App\Services\WithholdingTaxServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bill Payments')]
class BillPaymentForm extends Component
{

    public bool $IS_DOCTOR = false;
    public int $ID;
    public string $CODE;
    public bool $UNPOSTED = true;
    public $DATE;
    public int $PAY_TO_ID;
    public int $LOCATION_ID;
    public int $BANK_ACCOUNT_ID;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public float $WTAX_APPLIED;
    public string $NOTES;
    public int $TYPE   = 1;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION;
    public int $ACCOUNTS_PAYABLE_ID = 21;
    public bool $ppRefresh          = false;
    public int $PF_PERIOD_ID;
    public bool $SAME_AMOUNT = true;
    public $locationList     = [];
    public bool $Modify;
    public $contactList       = [];
    public $accountList       = [];
    public $paymentPeriodList = [];
    private $billPaymentServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $accountServices;
    private $documentStatusServices;
    private $accountJournalServices;
    private $paymentPeriodServices;
    private $withholdingTaxServices;
    private $systemSettingServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountServices $accountServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices,
        PaymentPeriodServices $paymentPeriodServices,
        WithholdingTaxServices $withholdingTaxServices,
        SystemSettingServices $systemSettingServices
    ) {
        $this->billPaymentServices    = $billPaymentServices;
        $this->contactServices        = $contactServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->accountServices        = $accountServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->paymentPeriodServices  = $paymentPeriodServices;
        $this->withholdingTaxServices = $withholdingTaxServices;
        $this->systemSettingServices  = $systemSettingServices;

    }
    #[On('reset-payment')]
    public function ResetPaymentApplied()
    {
        $this->AMOUNT_APPLIED = (float) $this->billPaymentServices->UpdateBillPaymentApplied($this->ID);
        $this->WTAX_APPLIED   = (float) $this->withholdingTaxServices->getAmountBetweenBillPayment($this->ID);
        $this->getNewAmount();
    }
    private function LoadDropDown()
    {
        $this->contactList  = $this->contactServices->getVendorDoc();
        $this->locationList = $this->locationServices->getList();
        $this->accountList  = $this->accountServices->getBankAccount();
        $this->updatedLocationid();
    }

    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->billPaymentServices->Get($id);
            if ($data) {

                $this->getInfo($data);
                $this->LoadDropDown();
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorsbill_payment')->with('error', $errorMessage);
        }

        $this->ID              = 0;
        $this->CODE            = '';
        $this->DATE            = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID     = $this->userServices->getLocationDefault();
        $this->AMOUNT          = 0;
        $this->NOTES           = '';
        $this->BANK_ACCOUNT_ID = 0;
        $this->PAY_TO_ID       = 0;
        $this->Modify          = true;
        $this->AMOUNT_APPLIED  = 0;
        $this->PF_PERIOD_ID    = 0;
        $this->LoadDropDown();
    }
    public function updatedLocationid()
    {

        $this->paymentPeriodList = $this->paymentPeriodServices->prPFList($this->LOCATION_ID ?? 0);
        $this->ppRefresh         = $this->ppRefresh ? false : true;
    }
    public function getInfo($data)
    {
        $this->ID              = $data->ID;
        $this->CODE            = $data->CODE;
        $this->DATE            = $data->DATE;
        $this->LOCATION_ID     = $data->LOCATION_ID;
        $this->AMOUNT          = $data->AMOUNT;
        $this->NOTES           = $data->NOTES ?? '';
        $this->BANK_ACCOUNT_ID = $data->BANK_ACCOUNT_ID;
        $this->PAY_TO_ID       = $data->PAY_TO_ID;
        $this->STATUS          = $data->STATUS;
        if ($this->STATUS == 16) {
            $this->removeJournal();
        }

        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        $this->Modify             = false;
        $this->IS_DOCTOR          = (bool) $this->contactServices->isDoctor($this->PAY_TO_ID);
        $this->PF_PERIOD_ID       = $data->PF_PERIOD_ID ?? 0;
    }
    public function getModify()
    {
        $this->Modify = true;

        if ($this->IS_DOCTOR) {
            $this->updatedLocationid();
        }
    }
    public function updateCancel()
    {
        $data = $this->billPaymentServices->Get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function save()
    {
        $this->validate(
            [
                'BANK_ACCOUNT_ID' => 'required|not_in:0|exists:account,id',
                'PAY_TO_ID'       => 'required|not_in:0|exists:contact,id',
                'CODE'            => 'nullable|max:20|unique:check,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'DATE'            => 'required|date',
                'LOCATION_ID'     => 'required|exists:location,id',

            ],
            [],
            [
                'PAY_TO_ID'       => 'Pay To',
                'BANK_ACCOUNT_ID' => 'Bank Account',
                'DATE'            => 'Date',
                'LOCATION_ID'     => 'Location',
                'CODE'            => 'Reference No.',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        try {
            if ($this->ID == 0) {
                DB::beginTransaction();
                $this->ID = $this->billPaymentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->BANK_ACCOUNT_ID,
                    $this->PAY_TO_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID
                );
                if ($this->PF_PERIOD_ID > 0) {
                    $this->billPaymentServices->UpdatePF_PERIOD_ID($this->ID, $this->PF_PERIOD_ID);
                }
                DB::commit();
                return Redirect::route('vendorsbill_payment_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                DB::beginTransaction();
                $data = $this->billPaymentServices->Get($this->ID);
                if ($data) {
                    if ($this->STATUS == 16) {
                        $JNO = $this->accountJournalServices->getRecord($this->billPaymentServices->object_type_check, $this->ID);
                        if ($JNO > 0) {
                            // BANK_ACCOUNT_ID on CREDIT
                            $this->accountJournalServices->AccountSwitch($this->BANK_ACCOUNT_ID, $data->BANK_ACCOUNT_ID, $this->LOCATION_ID, $JNO, $data->PAY_TO_ID, $this->ID, $this->billPaymentServices->object_type_check, $this->DATE, 1);
                            // BANK_ACCOUNT_ID on DEBIT
                            $this->accountJournalServices->AccountSwitch($this->BANK_ACCOUNT_ID, $data->BANK_ACCOUNT_ID, $this->LOCATION_ID, $JNO, $data->PAY_TO_ID, $this->ID, $this->billPaymentServices->object_type_check, $this->DATE, 0);
                        }
                    }

                    $this->billPaymentServices->Update($this->ID, $this->CODE, $this->BANK_ACCOUNT_ID, $this->PAY_TO_ID, $this->LOCATION_ID, $this->AMOUNT, $this->NOTES);
                    if ($this->PF_PERIOD_ID > 0) {
                        $this->billPaymentServices->UpdatePF_PERIOD_ID($this->ID, $this->PF_PERIOD_ID);
                    }

                    DB::commit();
                    session()->flash('message', 'Successfully updated');
                    $this->updatedLocationid();
                }
            }
            $this->Modify = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function getPosted()
    {
        try {
            DB::beginTransaction();
            $check      = $this->billPaymentServices->object_type_check;
            $checkbills = $this->billPaymentServices->object_type_check_bills;
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($check, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($check, $this->ID) + 1;
            }

            $checkDataBills = $this->billPaymentServices->billPaymentBillsJournal($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $checkDataBills,
                $this->LOCATION_ID,
                $checkbills,
                $this->DATE,
                "AP"
            );

            $checkData = $this->billPaymentServices->billPaymentJournalRemaining($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $checkData,
                $this->LOCATION_ID,
                $check,
                $this->DATE,
                "BILL"
            );

            $checkData = $this->billPaymentServices->billPaymentJournal($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $checkData,
                $this->LOCATION_ID,
                $check,
                $this->DATE,
                "BILL"
            );

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];
            if ($debit_sum == $credit_sum) {
                $this->billPaymentServices->StatusUpdate($this->ID, 15);
                DB::commit();
                $data = $this->billPaymentServices->get($this->ID);
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
            $this->billPaymentServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('vendorsbill_payment_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billPaymentServices->object_type_check, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }
    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billPaymentServices->object_type_check, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    public function DoctorPrint()
    {

    }
    #[On('reload_bill_list')]
    public function getNewAmount()
    {
        $data = $this->billPaymentServices->Get($this->ID);
        if ($data) {
            $this->AMOUNT = $data->AMOUNT ?? 0;
        }
    }
    public function updatedPayToId()
    {
        $this->ppRefresh = $this->ppRefresh ? false : true;
        $this->IS_DOCTOR = (bool) $this->contactServices->isDoctor($this->PAY_TO_ID);
        if ($this->IS_DOCTOR) {
            $this->updatedLocationid();
        }
    }
    public function render()
    {
        $this->AMOUNT_APPLIED = (float) $this->billPaymentServices->getTotalApplied($this->ID);
        $this->WTAX_APPLIED   = (float) $this->withholdingTaxServices->getAmountBetweenBillPayment($this->ID);
        return view('livewire.bill-payments.bill-payment-form');
    }
}
