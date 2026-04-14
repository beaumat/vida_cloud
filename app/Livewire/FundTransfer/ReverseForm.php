<?php
namespace App\Livewire\FundTransfer;

use App\Services\AccountJournalServices;
use App\Services\FundTransferReverseServices;
use App\Services\FundTransferServices;
use App\Services\LocationServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ReverseForm extends Component
{
    private $fundTransferServices;
    private $fundTransferReverseServices;
    private $accountJournalServices;

    private $locationServices;

    public $FUND_TRANSFER_ID;

    public string $DATE  = '';
    public string $NOTES = '';

    public int $LOCATION_ID;

    public int $ID;
    public bool $Modify = false;

    public $showModal         = false;
    public bool $DISPLAY_MODE = false;
    public $locationList      = [];
    public function boot(FundTransferServices $fundTransferServices, FundTransferReverseServices $fundTransferReverseServices, LocationServices $locationServices, AccountJournalServices $accountJournalServices)
    {
        $this->fundTransferServices        = $fundTransferServices;
        $this->fundTransferReverseServices = $fundTransferReverseServices;
        $this->locationServices            = $locationServices;
        $this->accountJournalServices      = $accountJournalServices;

    }

    public function mount(int $id)
    {
        $this->FUND_TRANSFER_ID = $id;
    }
    #[On('reload-data')]
    public function GetInfo()
    {

        $ft = $this->fundTransferReverseServices->GetFundTransferReverseByFundTransferID($this->FUND_TRANSFER_ID);
        if ($ft) {
            // RECORD FOUND, SHOW DETAILS
            $this->DATE         = $ft->DATE;
            $this->NOTES        = $ft->NOTES;
            $this->LOCATION_ID  = $ft->LOCATION_ID;
            $this->ID           = $ft->ID;
            $this->Modify       = false;
            $this->DISPLAY_MODE = true;
        } else {
            $ft = $this->fundTransferServices->Get($this->FUND_TRANSFER_ID);

            if ($ft) {
                // NEW
                $this->DATE        = $ft->DATE;
                $this->NOTES       = 'Reverse of ' . $ft->CODE;
                $this->LOCATION_ID = $ft->FROM_LOCATION_ID;

                $this->Modify = true;
            }

        }
    }

    public function saveData()
    {
        $exists = $this->fundTransferServices->Exists($this->FUND_TRANSFER_ID);
        if ($exists == false) {
            // not found!
            session()->flash('error', 'Fund Transfer does not exist.');
            return;
        }

        DB::beginTransaction();
        try {

            $this->ID = (int) $this->fundTransferReverseServices->Store($this->DATE, $this->NOTES, $this->FUND_TRANSFER_ID, $this->LOCATION_ID);
            if ($this->ID > 0) {
                // MAKE JOURNAL
                if (! $this->AccountJournal()) {
                    DB::rollBack();
                    return;
                }
            }
            DB::commit();
            session()->flash('success', 'Fund Transfer Reversed successfully.');
            $this->dispatch('refresh-fund-transfer');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    #[On('open-fund-transfer-reverse')]
    public function openModal()
    {
        $this->showModal    = true;
        $this->locationList = $this->locationServices->getList();
        $this->dispatch('reload-data');
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    private function AccountJournal(): bool
    {

        try {

            $tfData = $this->fundTransferServices->Get($this->FUND_TRANSFER_ID);

            $fundTransfer = $this->fundTransferReverseServices->object_type_id;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($fundTransfer, $this->ID);

            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($fundTransfer, $this->ID) + 1;
            }

            // Inter From
            $fundData = $this->fundTransferReverseServices->getJournalFrom($this->ID, $this->FUND_TRANSFER_ID, false, true);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $tfData->FROM_LOCATION_ID,
                $fundTransfer,
                $this->DATE
            );

            //From
            $fundData = $this->fundTransferReverseServices->getJournalFrom($this->ID, $this->FUND_TRANSFER_ID, true, false);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $tfData->FROM_LOCATION_ID,
                $fundTransfer,
                $this->DATE
            );

            // Inter TO
            $fundData = $this->fundTransferReverseServices->getJournalTo($this->ID, $this->FUND_TRANSFER_ID, true, true);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $tfData->TO_LOCATION_ID,
                $fundTransfer,
                $this->DATE
            );

            //TO
            $fundData = $this->fundTransferReverseServices->getJournalTo($this->ID, $this->FUND_TRANSFER_ID, false, false);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $fundData,
                $tfData->TO_LOCATION_ID,
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
    public function render()
    {
        return view('livewire.fund-transfer.reverse-form');
    }
}
