<?php
namespace App\Livewire\AccountingReport;

use App\Exports\TrialBalanceExport;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Trial Balance Report')]
class TrialBalanceReport extends Component
{

    public bool $IS_RANGE = true;
    public float $TOTAL_DEBIT  = 0;
    public float $TOTAL_CREDIT = 0;
    public $DATE_FROM;
    public $DATE_TO;

    public int $LOCATION_ID;
    public $locationList              = [];
    public $accountList               = [];
    public array $selectedAccount     = [];
    public $accountTypeList           = [];
    public array $selectedAccountType = [];
    public $dataList                  = [];
    private $accountJournalServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $accountServices;
    public function boot(
        AccountJournalServices $accountJournalServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountServices $accountServices
    ) {
        $this->accountJournalServices = $accountJournalServices;
        $this->dateServices           = $dateServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->accountServices        = $accountServices;
    }
    public function mount()
    {
        $this->TOTAL_DEBIT     = 0;
        $this->TOTAL_CREDIT    = 0;
        $this->updatedIsRange();
        $this->LOCATION_ID     = $this->userServices->getLocationDefault();
        $this->locationList    = $this->locationServices->getList();
        $this->accountList     = $this->accountServices->getAccount(false);
        $this->accountTypeList = $this->accountServices->GetTypeList();
    }
    public function updatedIsRange()
    {
        if ($this->IS_RANGE) {
            $this->DATE_FROM = $this->dateServices->GetFirstDay_Month($this->dateServices->NowDate());
            $this->DATE_TO   = $this->dateServices->NowDate();
        } else {
            $this->DATE_FROM = $this->dateServices->NowDate();
            $this->DATE_TO   = "";
        }
    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function generate()
    {
        $this->dataList = $this->accountJournalServices->getTrialBalance(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->selectedAccount,
            $this->selectedAccountType
        );
    }
    public function export()
    {

        if (! $this->dataList) {
            session()->flash('error', 'Please generate first');
            return;
        }

        return Excel::download(new TrialBalanceExport(
            $this->dataList
        ), 'trial-balance-export.xlsx');
    }
    public function render()
    {
        return view('livewire.accounting-report.trial-balance-report');
    }
}
