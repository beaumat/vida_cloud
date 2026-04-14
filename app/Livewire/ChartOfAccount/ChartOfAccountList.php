<?php
namespace App\Livewire\ChartOfAccount;

use App\Services\AccountJournalEndingServices;
use App\Services\AccountServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Chart Of Account')]
class ChartOfAccountList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    public $locationList = [];
    public int $locationid;
    private $accountServices;
    private $locationServices;
    private $userServices;
    private $accountJournalEndingServices;
    public bool $showAll = false;
    public bool $showBalance = false;
    public function boot(AccountServices $accountServices, LocationServices $locationServices, UserServices $userServices, AccountJournalEndingServices $accountJournalEndingServices)
    {
        $this->accountServices              = $accountServices;
        $this->locationServices             = $locationServices;
        $this->userServices                 = $userServices;
        $this->accountJournalEndingServices = $accountJournalEndingServices;
    }
    public function delete($id)
    {
        try {
            $this->accountServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount()
    {

        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function accountInactive(int $id, int $status)
    {
        $this->accountServices->Inactive($id, $status);
    }
    public function RecalculateAllAccount()
    {
        $accountList = $this->accountServices->AccountList();
        foreach ($accountList as $list) {
            $this->accountJournalEndingServices->ResetFirstEntryAccount($list->ID, $this->locationid);
        }
        session()->flash('message', 'just working on calculate ending balance');
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
        $dataList = $this->accountServices->Search($this->search, $this->locationid, $this->showAll, $this->showBalance);

        return view('livewire.chart-of-account.chart-of-account-list', ['dataList' => $dataList]);
    }
}
