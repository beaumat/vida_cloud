<?php

namespace App\Livewire\BankTransfer;

use App\Services\AccountJournalServices;
use App\Services\BankTransferServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bank Transfer')]
class BankTransferList extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $bankTransferServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    public function boot(
        BankTransferServices $bankTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->bankTransferServices = $bankTransferServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
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
    public function delete($id)
    {

        $data = $this->bankTransferServices->Get($id);

        if ($data) {


            try {
                DB::beginTransaction();

                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->bankTransferServices->object_type_id,
                    $id
                );

                if ($JOURNAL_NO > 0) {
                    $data = $this->bankTransferServices->get($id);
                    if ($data) {
                        // FROM
                        $this->accountJournalServices->DeleteJournal(
                            $data->FROM_BANK_ACCOUNT_ID,
                            $data->FROM_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->FROM_NAME_ID ?? 0,
                            $data->ID,
                            $this->bankTransferServices->object_type_id,
                            $data->DATE,
                            1
                        );
                        $this->accountJournalServices->DeleteJournal(
                            $data->INTER_LOCATION_ACCOUNT_ID,
                            $data->FROM_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->FROM_NAME_ID ?? 0,
                            $data->ID,
                            $this->bankTransferServices->object_type_id,
                            $data->DATE,
                            0
                        );



                        // TO
                        $this->accountJournalServices->DeleteJournal(
                            $data->TO_BANK_ACCOUNT_ID,
                            $data->TO_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->TO_NAME_ID ?? 0,
                            $data->ID,
                            $this->bankTransferServices->object_type_id,
                            $data->DATE,
                            0
                        );
                        // TO
                        $this->accountJournalServices->DeleteJournal(
                            $data->INTER_LOCATION_ACCOUNT_ID,
                            $data->TO_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->TO_NAME_ID ?? 0,
                            $data->ID,
                            $this->bankTransferServices->object_type_id,
                            $data->DATE,
                            1
                        );

                    }
                }

                $this->bankTransferServices->Delete($id);
                DB::commit();
                session()->flash('message', 'Successfully deleted.');
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMessage = 'Error occurred: ' . $e->getMessage();
                session()->flash('error', $errorMessage);
            }
            return;
        }
    }
    public function render()
    {
        $dataList = $this->bankTransferServices->Search($this->search, $this->locationid);

        return view('livewire.bank-transfer.bank-transfer-list', ['dataList' => $dataList]);
    }
}
