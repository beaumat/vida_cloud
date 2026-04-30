<?php

namespace App\Livewire\IncomeStatement;

use App\Services\AccountJournalServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Transaction Journal Viewer by Date-Range")]
class PettyCashStatementAccountSummary extends Component
{


    public float $TOTAL_DEBIT = 0.00;
    public float $TOTAL_CREDIT = 0.00;
    public $dataList = [];
    private $accountJournalServices;
    public function boot(AccountJournalServices $accountJournalServices)
    {
        $this->accountJournalServices = $accountJournalServices;
    }
    public function mount($id = null,  string $datefrom, string $dateto,int $locationid = 0)
    {   
        $this->dataList = $this->accountJournalServices->getTransactionJournalViewerSummary($id, $datefrom, $dateto, $locationid);
    }
    public function openDetails(int $JN)
    {
        $url = $this->accountJournalServices->getUrlBy($JN);
        $this->js("window.open('$url', '_blank')");
    }
    public function render()
    {
        return view('livewire.income-statement.income-statement-account-summary');
    }
}
