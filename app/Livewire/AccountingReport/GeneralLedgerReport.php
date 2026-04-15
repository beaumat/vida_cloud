<?php
namespace App\Livewire\AccountingReport;

use App\Exports\GeneralLedgerExport;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('General Ledger Report')]
class GeneralLedgerReport extends Component
{

    public bool $IS_RANGE = true;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList              = [];
    public $accountList               = [];
    public $accountTypeList           = [];
    public array $selectedAccount     = [];
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
        AccountServices $accountServices,

    ) {
        $this->accountJournalServices = $accountJournalServices;
        $this->locationServices       = $locationServices;
        $this->dateServices           = $dateServices;
        $this->userServices           = $userServices;
        $this->accountServices        = $accountServices;
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
    public function updatedDateFrom()
    {
        $this->DATE_TO = $this->dateServices->GetLastDay_Month($this->DATE_FROM);
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
    public function mount()
    {
        $this->updatedIsRange();
        $this->LOCATION_ID     = $this->userServices->getLocationDefault();
        $this->locationList    = $this->locationServices->getList();
        $this->accountList     = $this->accountServices->getAccount(false);
        $this->accountTypeList = $this->accountServices->GetTypeList();
    }

    public function export()
    {
        if (! $this->dataList) {
            session()->flash('error', 'Please generate first.');
            return;
        }
        return Excel::download(new GeneralLedgerExport(
            $this->dataList
        ), 'general-ledger-export.xlsx');
    }

    public function render()
    {
        return view('livewire.accounting-report.general-ledger-report');
    }
}
