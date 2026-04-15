<?php
namespace App\Livewire\AccountingReport;

use App\Services\AccountJournalServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Transaction Journal - Error')]
class TransactionJournalError extends Component
{

    private $selectedAccountType;
    private $selectedAccount;
    private $accountJournalServices;
    public $DATE_FROM;
    public $DATE_TO;
    public $LOCATION_ID;
    public $dataList = [];

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
    public function cleanData()
    {
        $this->accountJournalServices->getTransactionJournalErrorUpdate($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        session()->flash('message', 'Success clean');
    }
    public function Generete()
    {

        try {

            $this->dataList = $this->accountJournalServices->getTransactionJournalError(
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                $this->selectedAccount,
                $this->selectedAccountType
            );
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function export()
    {

    }
    public function setZero(int $JOURNAL_ID)
    {
        $this->accountJournalServices->setZeroUpdate($JOURNAL_ID);
        session()->flash('message', 'Success update');
    }
    public function openDetails(int $JN)
    {
        $url = $this->accountJournalServices->getUrlBy($JN);
        $this->js("window.open('$url', '_blank')");

    }
    public function render()
    {
        return view('livewire.accounting-report.transaction-journal-error');
    }
}
