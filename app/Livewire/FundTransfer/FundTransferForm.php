<?php
namespace App\Livewire\FundTransfer;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\FundTransferReverseServices;
use App\Services\FundTransferServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Fund Transfer')]
class FundTransferForm extends Component
{

    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $FROM_LOCATION_ID;
    public int $TO_LOCATION_ID;

    public int $FROM_ACCOUNT_ID;
    public int $TO_ACCOUNT_ID;

    public int $FROM_NAME_ID;
    public int $TO_NAME_ID;

    public float $AMOUNT;
    public string $NOTES;
    public bool $IS_REVERSE = false;
    public int $INTER_LOCATION_ACCOUNT_ID;
    public $interLocationAccountList = [];
    public $fromLocationList         = [];
    public $toLocationList           = [];

    public $fromContactList = [];
    public $toContactList   = [];

    public $fromAccountList = [];
    public $toAccountList   = [];

    public bool $Modify;
    private $fundTransferServices;
    private $locationServices;
    private $userServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;
    private $accountJournalServices;
    private $contactServices;
    private $accountServices;
    private $systemSettingServices;
    private $fundTransferReverseServices;
    public function boot(
        FundTransferServices $fundTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices,
        ContactServices $contactServices,
        AccountServices $accountServices,
        SystemSettingServices $systemSettingServices,
        FundTransferReverseServices $fundTransferReverseServices
    ) {

        $this->fundTransferServices        = $fundTransferServices;
        $this->locationServices            = $locationServices;
        $this->userServices                = $userServices;
        $this->documentStatusServices      = $documentStatusServices;
        $this->accountJournalServices      = $accountJournalServices;
        $this->contactServices             = $contactServices;
        $this->accountServices             = $accountServices;
        $this->systemSettingServices       = $systemSettingServices;
        $this->fundTransferReverseServices = $fundTransferReverseServices;

    }
    public function LoadDropdown()
    {
        $dataLocationList       = $this->locationServices->getList();
        $this->fromLocationList = $dataLocationList;
        $this->toLocationList   = $dataLocationList;

        $dataContactList       = $this->contactServices->getListAllType();
        $this->fromContactList = $dataContactList;
        $this->toContactList   = $dataContactList;

        $acctList                       = $this->accountServices->getAccount(false);
        $this->fromAccountList          = $acctList;
        $this->toAccountList            = $acctList;
        $this->interLocationAccountList = $acctList;
    }

    private function getInfo($data)
    {
        $this->ID                        = $data->ID;
        $this->CODE                      = $data->CODE;
        $this->DATE                      = $data->DATE;
        $this->AMOUNT                    = $data->AMOUNT ?? 0;
        $this->FROM_ACCOUNT_ID           = $data->FROM_ACCOUNT_ID ?? 0;
        $this->TO_ACCOUNT_ID             = $data->TO_ACCOUNT_ID ?? 0;
        $this->FROM_LOCATION_ID          = $data->FROM_LOCATION_ID ?? 0;
        $this->TO_LOCATION_ID            = $data->TO_LOCATION_ID ?? 0;
        $this->INTER_LOCATION_ACCOUNT_ID = $data->INTER_LOCATION_ACCOUNT_ID ?? 0;
        $this->TO_NAME_ID                = $data->TO_NAME_ID ?? 0;
        $this->FROM_NAME_ID              = $data->FROM_NAME_ID ?? 0;
        $this->NOTES                     = $data->NOTES ?? '';
        $this->STATUS                    = $data->STATUS ?? 0;
        if ($this->STATUS == 16) {
            $this->removeJournal();
        }
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            try {
                $data = $this->fundTransferServices->Get($id);
                if ($data) {
                    $this->LoadDropdown();
                    $this->getInfo($data);
                    $this->Modify     = false;
                    $this->IS_REVERSE = $this->fundTransferReverseServices->ExistsByFundTransferID($id);
                    return;
                }

            } catch (\Throwable $th) {
                //throw $th;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingfund_transfer')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->Modify          = true;
        $this->ID              = 0;
        $this->CODE            = '';
        $this->DATE            = $this->userServices->getTransactionDateDefault();
        $this->FROM_ACCOUNT_ID = 0;
        $this->TO_ACCOUNT_ID   = 0;

        $this->INTER_LOCATION_ACCOUNT_ID = 0;
        $this->FROM_LOCATION_ID          = $this->userServices->getLocationDefault();
        $this->TO_LOCATION_ID            = 0;

        $this->FROM_NAME_ID = 0;
        $this->TO_NAME_ID   = 0;
        $this->AMOUNT       = 0;
        $this->NOTES        = '';

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
                'CODE'                      => 'nullable|max:20|unique:fund_transfer,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'DATE'                      => 'required|date',
                'FROM_LOCATION_ID'          => 'required|exists:location,id',
                'TO_LOCATION_ID'            => 'required|exists:location,id',
                'FROM_ACCOUNT_ID'           => 'required|exists:account,id',
                'TO_ACCOUNT_ID'             => 'required|exists:account,id',
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
                'FROM_ACCOUNT_ID'           => 'From Account',
                'TO_ACCOUNT_ID'             => 'To Account',
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

                $this->ID = $this->fundTransferServices->Store(
                    $this->DATE,
                    $this->CODE,
                    $this->FROM_ACCOUNT_ID,
                    $this->TO_ACCOUNT_ID,
                    $this->FROM_NAME_ID,
                    $this->TO_NAME_ID,
                    $this->FROM_LOCATION_ID,
                    $this->TO_LOCATION_ID,
                    $this->INTER_LOCATION_ACCOUNT_ID,
                    $this->NOTES,
                    $this->AMOUNT
                );

                DB::commit();
                return Redirect::route('bankingfund_transfer_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                if ($this->STATUS == 16) {
                    $this->editJournal();
                }

                $this->fundTransferServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->FROM_ACCOUNT_ID,
                    $this->TO_ACCOUNT_ID,
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
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->fundTransferServices->object_type_id, $this->ID);
            $this->updateJournal($JOURNAL_NO, 1, $this->FROM_ACCOUNT_ID, $this->FROM_NAME_ID, $this->FROM_LOCATION_ID, $data->FROM_ACCOUNT_ID);
            $this->updateJournal($JOURNAL_NO, 0, $this->INTER_LOCATION_ACCOUNT_ID, $this->FROM_NAME_ID, $this->FROM_LOCATION_ID, $data->INTER_LOCATION_ACCOUNT_ID);
            $this->updateJournal($JOURNAL_NO, 0, $this->TO_ACCOUNT_ID, $this->TO_NAME_ID, $this->TO_LOCATION_ID, $data->TO_ACCOUNT_ID);
            $this->updateJournal($JOURNAL_NO, 1, $this->INTER_LOCATION_ACCOUNT_ID, $this->TO_NAME_ID, $this->TO_LOCATION_ID, $data->INTER_LOCATION_ACCOUNT_ID);
        }
    }
    private function updateJournal(int $JOURNAL_NO, int $ENTRY_TYPE, int $ACCOUNT_ID, int $NAME_ID, int $LOCATION_ID, int $PREV_ACCOUNT_ID)
    {
        if ($JOURNAL_NO > 0) {

            $this->accountJournalServices->parameterUpdate([
                ['JOURNAL_NO', '=', $JOURNAL_NO],
                ['OBJECT_TYPE', '=', $this->fundTransferServices->object_type_id],
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
            $fundTransfer = $this->fundTransferServices->object_type_id;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($fundTransfer, $this->ID);

            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($fundTransfer, $this->ID) + 1;
            }

            // Inter From
            $fundData = $this->fundTransferServices->getJournalFrom($this->ID, true, true);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $this->FROM_LOCATION_ID,
                $fundTransfer,
                $this->DATE
            );

            //From
            $fundData = $this->fundTransferServices->getJournalFrom($this->ID, false, false);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $this->FROM_LOCATION_ID,
                $fundTransfer,
                $this->DATE
            );

            // Inter TO
            $fundData = $this->fundTransferServices->getJournalTo($this->ID, false, true);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $this->TO_LOCATION_ID,
                $fundTransfer,
                $this->DATE
            );

            //TO
            $fundData = $this->fundTransferServices->getJournalTo($this->ID, true, false);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $this->TO_LOCATION_ID,
                $fundTransfer,
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
        return Redirect::route('bankingfund_transfer_edit', ['id' => $this->ID]);
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
            $this->fundTransferServices->StatusUpdate($this->ID, 15);
            DB::commit();

            $data = $this->fundTransferServices->get($this->ID);
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
    public function OpenJournalReverse()
    {
        $dataReverse = $this->fundTransferReverseServices->GetFundTransferReverseByFundTransferID($this->ID);
        if ($dataReverse) {
            $JOURNAL_NO = $this->accountJournalServices->getRecord(
                $this->fundTransferReverseServices->object_type_id,
                $dataReverse->ID
            );

            if ($JOURNAL_NO > 0) {
                $data = ['JOURNAL_NO' => $JOURNAL_NO];
                $this->dispatch('open-journal', result: $data);
                return;
            }
            session()->flash('error', 'Journal entry not created');
        }

    }
    public function OpenJournal()
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->fundTransferServices->object_type_id,
            $this->ID
        );

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
            return;
        }

        session()->flash('error', 'Journal entry not created');
    }
    #[On('refresh-fund-transfer')]
    public function refreshThisForm()
    {
        Redirect::route('bankingfund_transfer_edit', $this->ID);
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->fundTransferServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('bankingfund_transfer_edit', $this->ID);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->fundTransferServices->object_type_id, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function getReverse()
    {
        $this->dispatch('open-fund-transfer-reverse');
    }
    public function render()
    {
        return view('livewire.fund-transfer.fund-transfer-form');
    }
}
