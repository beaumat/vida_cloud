<?php

namespace App\Livewire\Deposit;

use App\Services\AccountJournalServices;
use App\Services\DepositServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bank Deposit')]
class DepositList extends Component
{
    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $depositServices;
    private $userServices;
    private $locationServices;
    private $accountJournalServices;
    public function boot(DepositServices $depositServices, LocationServices $locationServices, UserServices $userServices, AccountJournalServices $accountJournalServices)
    {
        $this->depositServices = $depositServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function deleteJournal($data)
    {
        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->depositServices->object_type_deposit, $data->ID);
        $fundData = $this->depositServices->FundList($data->ID);

        foreach ($fundData as $list) {
            $this->accountJournalServices->DeleteJournal(
                $list->ACCOUNT_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $list->RECEIVED_FROM_ID,
                $list->ID,
                $this->depositServices->object_type_deposit_fund,
                $data->DATE,
                1
            );
        }

        $this->accountJournalServices->DeleteJournal(
            $data->BANK_ACCOUNT_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            0,
            $data->ID,
            $this->depositServices->object_type_deposit,
            $data->DATE,
            0
        );

    }
    public function delete($id)
    {
        $data = $this->depositServices->Get($id);

        if (!$data) {
            return;
        }

        DB::beginTransaction();
        try {

            if ($data->STATUS == 15 || $data->STATUS == 16) {
                $this->deleteJournal($data);
            }
            $fundlist = $this->depositServices->FundList($id);
            foreach ($fundlist as $list) {
                $this->depositServices->UndepositedUpdate($list->SOURCE_OBJECT_ID, $list->SOURCE_OBJECT_TYPE, 0);
                $this->depositServices->DeleteFund($list->ID, $id);
            }

            $this->depositServices->Delete($id);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {

        $dataList = $this->depositServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.deposit.deposit-list', ['dataList' => $dataList]);
    }
}
