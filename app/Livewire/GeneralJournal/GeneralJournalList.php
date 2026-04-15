<?php

namespace App\Livewire\GeneralJournal;

use App\Services\AccountJournalServices;
use App\Services\GeneralJournalServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("General Journal")]
class GeneralJournalList extends Component
{
    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $generalJournalServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    public function boot(
        GeneralJournalServices $generalJournalServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->generalJournalServices = $generalJournalServices;
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

        $data = $this->generalJournalServices->Get($id);

        if ($data) {


            try {
                DB::beginTransaction();

                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->generalJournalServices->object_type_general_journal_details_id,
                    $id
                );

                if ($JOURNAL_NO  >  0) {
                    $dataList = $this->generalJournalServices->ListDetails($id);
                    foreach ($dataList as $list) {
                        $this->accountJournalServices->DeleteJournal(
                            $list->ACCOUNT_ID,
                            $data->LOCATION_ID,
                            $JOURNAL_NO,
                            0,
                            $list->ID,
                            $this->generalJournalServices->object_type_general_journal_details_id,
                            $data->DATE,
                            $list->ENTRY_TYPE
                        );
                    }
                }

                $this->generalJournalServices->Delete($id);
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
        $dataList = $this->generalJournalServices->Search($this->search, $this->locationid, $this->perPage);
        
        return view('livewire.general-journal.general-journal-list', ['dataList' => $dataList]);
    }
}
