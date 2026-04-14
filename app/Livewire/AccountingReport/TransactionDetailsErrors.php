<?php

namespace App\Livewire\AccountingReport;

use App\Services\AccountJournalServices;
use Livewire\Component;

class TransactionDetailsErrors extends Component
{

    public array $selectedAccount     = [];
    public array $selectedAccountType = [];
    public string $TEMP_ACCOUNT       = "";
    public float $TEMP_DEBIT          = 0;
    public float $TEMP_CREDIT         = 0;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public float $TOTAL_DEBIT  = 0;
    public float $TOTAL_CREDIT = 0;
    public float $BALANCE      = 0;
    public $dataList           = [];
    private $accountJournalServices;
    public function boot(AccountJournalServices $accountJournalServices)
    {
        $this->accountJournalServices = $accountJournalServices;
    }
    public function mount($from, $to, $location, string $account, string $accounttype)
    {
        $this->DATE_FROM           = $from;
        $this->DATE_TO             = $to;
        $this->LOCATION_ID         = $location;
        $this->selectedAccount     = $account !== 'none' ? explode(',', $account) : [];
        $this->selectedAccountType = $accounttype !== 'none' ? explode(',', $accounttype) : [];
        $this->Generete();
    }

    public function Generete()
    {

        $this->dataList = $this->accountJournalServices->getAccountTransaction(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->selectedAccount,
            $this->selectedAccountType
        );
    }
 
    public function openDetails(int $JN)
    {
        $url = $this->accountJournalServices->getUrlBy($JN);

        $this->js("window.open('$url', '_blank')");

    }
    public function render()
    {
        return view('livewire.accounting-report.transaction-details-errors');
    }
}
