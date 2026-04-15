<?php
namespace App\Livewire\AccountJournal;

use App\Services\AccountJournalServices;
use Livewire\Attributes\On;
use Livewire\Component;

class AccountJournalModal extends Component
{
    public bool $showModal = false;
    public $dataList       = [];
    public int $JOURNAL_NO;
    public $TOTAL_DEBIT  = 0;
    public $TOTAL_CREDIT = 0;
    private $accountJournalServices;
    public function boot(AccountJournalServices $accountJournalServices)
    {
        $this->accountJournalServices = $accountJournalServices;
    }
    #[On('open-journal')]
    public function openModal($result)
    {
        $this->TOTAL_DEBIT  = 0;
        $this->TOTAL_CREDIT = 0;
        $this->JOURNAL_NO   = $result['JOURNAL_NO'];
        $this->showModal    = true;

        $this->dataList = $this->accountJournalServices->getJournalList($this->JOURNAL_NO);
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.account-journal.account-journal-modal');
    }
}
