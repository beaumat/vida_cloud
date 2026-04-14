<?php
namespace App\Livewire\Deposit;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DepositServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bank Deposit')]
class DepositForm extends Component
{
    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $BANK_ACCOUNT_ID;
    public float $AMOUNT;
    public string $NOTES;
    public int $CASH_BACK_ACCOUNT_ID;
    public float $CASH_BACK_AMOUNT;
    public string $CASH_BACK_NOTES;
    public int $LOCATION_ID;
    public int $STATUS;
    public bool $Modify = true;
    public $STATUS_DESCRIPTION;
    public $accountList  = [];
    public $locationList = [];
    private $depositServices;
    private $accountServices;
    private $locationServices;
    private $userServices;
    private $documentStatusServices;
    private $accountJournalServices;
    private $systemSettingServices;
    public function boot(
        DepositServices $depositServices,
        AccountServices $accountServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices,
        SystemSettingServices $systemSettingServices

    ) {
        $this->depositServices        = $depositServices;
        $this->accountServices        = $accountServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->systemSettingServices  = $systemSettingServices;

    }
    private function loadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->accountList  = $this->accountServices->getBankAccount();
    }
    public function mount($id = null)
    {

        $this->loadDropdown();

        if (is_numeric($id)) {

            $data = $this->depositServices->Get($id);

            if ($data) {
                $this->refreshInfo($data);

                $this->Modify = false;
                return;
            }

            return Redirect::route('bankingdeposit')->with('Record not found');
        }
        // New
        $this->ID              = 0;
        $this->CODE            = '';
        $this->DATE            = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID     = $this->userServices->getLocationDefault();
        $this->STATUS          = 0;
        $this->BANK_ACCOUNT_ID = 0;
        $this->AMOUNT          = 0;
        $this->NOTES           = '';

        $this->CASH_BACK_ACCOUNT_ID = 0;
        $this->CASH_BACK_AMOUNT     = 0;
        $this->CASH_BACK_NOTES      = '';
        $this->Modify               = true;
    }
    public function save()
    {

        $this->validate(
            [
                'DATE'            => 'required|date',
                'LOCATION_ID'     => 'required|numeric|exists:location,id',
                'BANK_ACCOUNT_ID' => 'required|numeric|exists:account,id',
                'CODE'            => $this->ID > 0 ? 'required|max:20|unique:deposit,code,' . $this->ID : 'nullable',

            ],
            [],
            [
                'DATE'            => 'Date',
                'LOCATION_ID'     => 'Location',
                'BANK_ACCOUNT_ID' => 'Bank Account',
                'CODE'            => 'Reference No.',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        DB::beginTransaction();

        try {
            if ($this->ID == 0) {

                $this->ID = $this->depositServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->BANK_ACCOUNT_ID,
                    $this->NOTES,
                    $this->CASH_BACK_ACCOUNT_ID,
                    $this->CASH_BACK_AMOUNT,
                    $this->CASH_BACK_NOTES,
                    $this->LOCATION_ID
                );
                DB::commit();
                return Redirect::route('bankingdeposit_edit', ['id' => $this->ID]);
            } else {

                $data = $this->depositServices->Get($this->ID);
                if ($data) {

                    if ($this->STATUS == 16) {
                        // possible having change date
                        $JNO = (int) $this->accountJournalServices->getRecord($this->depositServices->object_type_deposit, $this->ID);
                        if ($JNO > 0) {
                            $this->accountJournalServices->updateObjectDate($JNO, $this->DATE);
                            // ACCOUNTS_PAYABLE_ID
                            $this->accountJournalServices->AccountSwitch(
                                $this->BANK_ACCOUNT_ID,
                                $data->BANK_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                0,
                                $this->ID,
                                $this->depositServices->object_type_deposit,
                                $this->DATE,
                                0
                            );
                        }

                    }
                }

                $this->depositServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->BANK_ACCOUNT_ID,
                    $this->NOTES,
                    $this->CASH_BACK_ACCOUNT_ID,
                    $this->CASH_BACK_AMOUNT,
                    $this->CASH_BACK_NOTES
                );

                DB::commit();
                session()->flash('message', 'Successfully updated');
                $this->updateCancel();
            }
        } catch (\Exception $e) {

            DB::rollback();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updateCancel()
    {
        $data = $this->depositServices->get($this->ID);
        if ($data) {
            $this->refreshInfo($data);
        }
        $this->Modify = false;
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->depositServices->StatusUpdate($this->ID, 16);
            DB::commit();
            Redirect::route('bankingdeposit_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->depositServices->object_type_deposit, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    public function getPosted()
    {
        try {
            DB::beginTransaction();

            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->depositServices->object_type_deposit, $this->ID);

            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($this->depositServices->object_type_deposit, $this->ID) + 1;
            }

            $depositData = $this->depositServices->DepositJournal($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $depositData,
                $this->LOCATION_ID,
                $this->depositServices->object_type_deposit,
                $this->DATE,
                ""
            );

            $depositFundData = $this->depositServices->DepositFundJournal($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $depositFundData,
                $this->LOCATION_ID,
                $this->depositServices->object_type_deposit_fund,
                $this->DATE,
                "FUND"
            );

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                $this->depositServices->StatusUpdate($this->ID, 15);
                DB::commit();
                $data = $this->depositServices->Get($this->ID);
                if ($data) {
                    $this->refreshInfo($data);
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
    private function refreshInfo($data)
    {

        $this->ID                   = $data->ID ?? 0;
        $this->CODE                 = $data->CODE ?? '';
        $this->DATE                 = $data->DATE;
        $this->LOCATION_ID          = $data->LOCATION_ID;
        $this->STATUS               = $data->STATUS;
        $this->BANK_ACCOUNT_ID      = $data->BANK_ACCOUNT_ID;
        $this->AMOUNT               = $data->AMOUNT ?? 0;
        $this->NOTES                = $data->NOTES ?? '';
        $this->CASH_BACK_ACCOUNT_ID = $data->CASH_BACK_ACCOUNT_ID ?? 0;
        $this->CASH_BACK_AMOUNT     = $data->CASH_BACK_AMOUNT ?? 0;
        $this->CASH_BACK_NOTES      = $data->CASH_BACK_NOTES ?? '';
        $this->STATUS               = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION   = $this->documentStatusServices->getDesc($this->STATUS);
    }
    #[On('get-amount')]
    public function UpdateAmount()
    {
        $this->AMOUNT = $this->depositServices->getAmount($this->ID);
    }
    public function openPayment()
    {
        $this->dispatch('open-payment', result: ['DEPOSIT_ID' => $this->ID, 'LOCATION_ID' => $this->LOCATION_ID]);
    }
    public function render()
    {
        return view('livewire.deposit.deposit-form');
    }
}
