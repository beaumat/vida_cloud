<?php
namespace App\Livewire\BankTransfer;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BankTransferServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bank Transfer')]
class BankTransferForm extends Component
{

    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $FROM_LOCATION_ID;
    public int $TO_LOCATION_ID;

    public int $FROM_BANK_ACCOUNT_ID;
    public int $TO_BANK_ACCOUNT_ID;

    public int $FROM_NAME_ID;
    public int $TO_NAME_ID;

    public float $AMOUNT;
    public string $NOTES;

    public int $INTER_LOCATION_ACCOUNT_ID;
    public $interLocationAccountList = [];
    public $fromLocationList         = [];
    public $toLocationList           = [];

    public $fromContactList = [];
    public $toContactList   = [];

    public $fromAccountList = [];
    public $toAccountList   = [];

    public bool $Modify;
    private $bankTransferServices;
    private $locationServices;
    private $userServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;
    private $accountJournalServices;
    private $contactServices;
    private $accountServices;
    private $systemSettingServices;
    public function boot(
        BankTransferServices $bankTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices,
        ContactServices $contactServices,
        AccountServices $accountServices,
        SystemSettingServices $systemSettingServices
    ) {

        $this->bankTransferServices   = $bankTransferServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->contactServices        = $contactServices;
        $this->accountServices        = $accountServices;
        $this->systemSettingServices  = $systemSettingServices;
    }
    public function LoadDropdown()
    {
        $dataLocationList       = $this->locationServices->getList();
        $this->fromLocationList = $dataLocationList;
        $this->toLocationList   = $dataLocationList;

        $dataContactList       = $this->contactServices->getListAllType();
        $this->fromContactList = $dataContactList;
        $this->toContactList   = $dataContactList;

        $bankList                       = $this->accountServices->getBankAccount();
        $this->fromAccountList          = $bankList;
        $this->toAccountList            = $bankList;
        $acctList                       = $this->accountServices->getAccount(false);
        $this->interLocationAccountList = $acctList;
    }

    private function getInfo($data)
    {
        $this->ID                        = $data->ID;
        $this->CODE                      = $data->CODE;
        $this->DATE                      = $data->DATE;
        $this->AMOUNT                    = $data->AMOUNT ?? 0;
        $this->FROM_BANK_ACCOUNT_ID      = $data->FROM_BANK_ACCOUNT_ID ?? 0;
        $this->TO_BANK_ACCOUNT_ID        = $data->TO_BANK_ACCOUNT_ID ?? 0;
        $this->FROM_LOCATION_ID          = $data->FROM_LOCATION_ID ?? 0;
        $this->TO_LOCATION_ID            = $data->TO_LOCATION_ID ?? 0;
        $this->INTER_LOCATION_ACCOUNT_ID = $data->INTER_LOCATION_ACCOUNT_ID ?? 0;
        $this->TO_NAME_ID                = $data->TO_NAME_ID ?? 0;
        $this->FROM_NAME_ID              = $data->FROM_NAME_ID ?? 0;
        $this->NOTES                     = $data->NOTES ?? '';
        $this->STATUS                    = $data->STATUS ?? 0;

        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->bankTransferServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingbank_transfer')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->Modify               = true;
        $this->ID                   = 0;
        $this->CODE                 = '';
        $this->DATE                 = $this->userServices->getTransactionDateDefault();
        $this->FROM_BANK_ACCOUNT_ID = 0;
        $this->TO_BANK_ACCOUNT_ID   = 0;

        $this->INTER_LOCATION_ACCOUNT_ID = 0;

        $this->FROM_LOCATION_ID = $this->userServices->getLocationDefault();
        $this->TO_LOCATION_ID   = 0;

        $this->FROM_NAME_ID = 0;
        $this->TO_NAME_ID   = 0;
        $this->AMOUNT       = 0;

        $this->NOTES = '';

        $this->STATUS             = 0;
        $this->STATUS_DESCRIPTION = '';
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function save()
    {

        $this->validate(
            [
                'CODE'                      => 'nullable|max:20|unique:bank_transfer,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'DATE'                      => 'required|date',
                'FROM_LOCATION_ID'          => 'required|exists:location,id',
                'TO_LOCATION_ID'            => 'required|exists:location,id',
                'FROM_BANK_ACCOUNT_ID'      => 'required|exists:account,id',
                'TO_BANK_ACCOUNT_ID'        => 'required|exists:account,id',
                'INTER_LOCATION_ACCOUNT_ID' => 'required|exists:account,id',
                'FROM_NAME_ID'              => $this->FROM_NAME_ID > 0 ? 'exists:contact,id' : 'nullable',
                'TO_NAME_ID'                => $this->TO_NAME_ID > 0 ? 'exists:contact,id' : 'nullable',
                'AMOUNT'                    => 'required|numeric|not_in:0',
            ],
            [],
            [
                'CODE'                      => 'Reference No.',
                'DATE'                      => 'Date',
                'FROM_LOCATION_ID'          => 'From Location',
                'TO_LOCATION_ID'            => 'To Location',
                'FROM_BANK_ACCOUNT_ID'      => 'From Bank Account',
                'TO_BANK_ACCOUNT_ID'        => 'To Bank Account',
                'INTER_LOCATION_ACCOUNT_ID' => 'Inter Location Account',
                'FROM_NAME_ID'              => 'From Name',
                'TO_NAME_ID'                => 'To Name',
                'AMOUNT'                    => 'Amount Fund',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        DB::beginTransaction();
        try {
            if ($this->ID == 0) {
                $this->ID = $this->bankTransferServices->Store(
                    $this->DATE,
                    $this->CODE,
                    $this->FROM_BANK_ACCOUNT_ID,
                    $this->TO_BANK_ACCOUNT_ID,
                    $this->FROM_NAME_ID,
                    $this->TO_NAME_ID,
                    $this->FROM_LOCATION_ID,
                    $this->TO_LOCATION_ID,
                    $this->INTER_LOCATION_ACCOUNT_ID,
                    $this->NOTES,
                    $this->AMOUNT
                );
                $this->bankTransferServices->StatusUpdate($this->ID, 0);
                DB::commit();
                return Redirect::route('bankingbank_transfer_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                if ($this->STATUS == 16) {
                    $this->editJournal();
                }
                $this->bankTransferServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->FROM_BANK_ACCOUNT_ID,
                    $this->TO_BANK_ACCOUNT_ID,
                    $this->FROM_NAME_ID,
                    $this->TO_NAME_ID,
                    $this->FROM_LOCATION_ID,
                    $this->TO_LOCATION_ID,
                    $this->INTER_LOCATION_ACCOUNT_ID,
                    $this->NOTES,
                    $this->AMOUNT

                );
                session()->flash('message', 'Successfully updated');
                DB::commit();
                $this->updateCancel();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    private function editJournal()
    {

        $data = $this->fundTransferServices->Get($this->ID);

        if ($data) {
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->bankTransferServices->object_type_id, $this->ID);
            $this->updateJournal($JOURNAL_NO, 1, $this->FROM_BANK_ACCOUNT_ID, $this->FROM_NAME_ID, $this->FROM_LOCATION_ID, $data->FROM_BANK_ACCOUNT_ID);
            $this->updateJournal($JOURNAL_NO, 0, $this->INTER_LOCATION_ACCOUNT_ID, $this->FROM_NAME_ID, $this->FROM_LOCATION_ID, $data->INTER_LOCATION_ACCOUNT_ID);
            $this->updateJournal($JOURNAL_NO, 0, $this->TO_BANK_ACCOUNT_ID, $this->TO_NAME_ID, $this->TO_LOCATION_ID, $data->TO_BANK_ACCOUNT_ID);
            $this->updateJournal($JOURNAL_NO, 1, $this->INTER_LOCATION_ACCOUNT_ID, $this->TO_NAME_ID, $this->TO_LOCATION_ID, $data->INTER_LOCATION_ACCOUNT_ID);
        }
    }
    private function updateJournal(int $JOURNAL_NO, int $ENTRY_TYPE, int $ACCOUNT_ID, int $NAME_ID, int $LOCATION_ID, int $PREV_ACCOUNT_ID)
    {
        if ($JOURNAL_NO > 0) {

            $this->accountJournalServices->parameterUpdate([
                ['JOURNAL_NO', '=', $JOURNAL_NO],
                ['OBJECT_TYPE', '=', $this->bankTransferServices->object_type_id],
                ['OBJECT_ID', '=', $this->ID],
                ['ENTRY_TYPE', '=', $ENTRY_TYPE],
                ['LOCATION_ID', '=', $LOCATION_ID],
                ['ACCOUNT_ID', '=', $PREV_ACCOUNT_ID],

            ], [
                'AMOUNT'        => $this->AMOUNT,
                'ACCOUNT_ID'    => $ACCOUNT_ID,
                'SUBSIDIARY_ID' => $NAME_ID,
            ]);
        }
    }
    private function AccountJournal(): bool
    {

        try {
            $bankTransfer = $this->bankTransferServices->object_type_id;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($bankTransfer, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($bankTransfer, $this->ID) + 1;
            }
            // Inter From
            $bankData = $this->bankTransferServices->getJournalFrom($this->ID, true, true);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $bankData,
                $this->FROM_LOCATION_ID,
                $bankTransfer,
                $this->DATE
            );

            //From
            $bankData = $this->bankTransferServices->getJournalFrom($this->ID, false, false);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $bankData,
                $this->FROM_LOCATION_ID,
                $bankTransfer,
                $this->DATE
            );

            // Inter TO
            $bankData = $this->bankTransferServices->getJournalTo($this->ID, false, true);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $bankData,
                $this->TO_LOCATION_ID,
                $bankTransfer,
                $this->DATE
            );

            //TO
            $bankData = $this->bankTransferServices->getJournalTo($this->ID, true, false);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $bankData,
                $this->TO_LOCATION_ID,
                $bankTransfer,
                $this->DATE
            );

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum && $debit_sum > 0 && $credit_sum > 0) {
                return true;
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            return false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function updateCancel()
    {
        return Redirect::route('bankingbank_transfer_edit', ['id' => $this->ID]);
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function posted()
    {
        try {

            if ($this->AMOUNT <= 0) {
                Session()->flash('error', 'Invalid amount fund.');
                return;
            }
            DB::beginTransaction();
            if (! $this->AccountJournal()) {
                DB::rollBack();
                return;
            }
            $this->bankTransferServices->StatusUpdate($this->ID, 15);
            DB::commit();

            $data = $this->bankTransferServices->get($this->ID);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
            }

            Session()->flash('message', 'Successfully posted');
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function print()
    {
    }
    public function OpenJournal()
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->bankTransferServices->object_type_id,
            $this->ID
        );

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
            return;
        }

        session()->flash('error', 'Journal entry not created');
    }

    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->bankTransferServices->StatusUpdate($this->ID, 16);
            DB::commit();
            Redirect::route('bankingbank_transfer_edit', $this->ID);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.bank-transfer.bank-transfer-form');
    }
}
