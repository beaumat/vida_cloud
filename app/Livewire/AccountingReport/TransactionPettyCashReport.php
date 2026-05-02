<?php
namespace App\Livewire\AccountingReport;

use App\Exports\TransactionJournalReportExport;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Transaction Journal Report')]
class TransactionPettyCashReport extends Component
{

    public $TOTAL_DEBIT  = 0;
    public $TOTAL_CREDIT = 0;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList              = [];
    public $accountList               = [];
    public array $selectedAccount     = [];
    public $accountTypeList           = [];
    public array $selectedAccountType = [];
    public string $url;
    public $dataList = [];
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
        $this->locationServices       = $locationServices;
        $this->dateServices           = $dateServices;
        $this->userServices           = $userServices;
        $this->accountServices        = $accountServices;
    }
    public function mount()
    {
        $this->DATE_TO         = $this->dateServices->NowDate();
        $this->DATE_FROM       = $this->dateServices->GetFirstDay_Month($this->DATE_TO);
        $this->LOCATION_ID     = $this->userServices->getLocationDefault();
        $this->locationList    = $this->locationServices->getList();
        $this->accountList     = $this->accountServices->getAccount(false);
        $this->accountTypeList = $this->accountServices->GetTypeList();
    }
    public function generate()
    {
        $this->TOTAL_DEBIT  = 0;
        $this->TOTAL_CREDIT = 0;
        $this->dataList     = $this->accountJournalServices->getTransactionJournal($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, $this->selectedAccount, $this->selectedAccountType);
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

    public function export()
    {

        $dataActual = $this->accountJournalServices->getTransactionJournal(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->selectedAccount,
            $this->selectedAccountType
        );

        return Excel::download(new TransactionJournalReportExport(
            $dataActual,
        ), 'transaction-journal-export.xlsx');
    }
    public function openDetails(int $JN)
    {
        $url = $this->accountJournalServices->getUrlBy($JN);
        $this->js("window.open('$url', '_blank')");

    }
    public function render()
    {
        return view('livewire.accounting-report.transaction-journal-report');
    }
}
