<?php
namespace App\Livewire\ChartOfAccount;

use App\Services\AccountJournalEndingServices;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\LocationServices;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Chart Of Account - Balaance')]
class ChartOfAccountEndingBalance extends Component
{

    public string $ACCOUNT_NAME;
    public string $LOCATION_NAME;
    public $dataList = [];
    private $accountJournalServices;
    private $accountJournalEndingServices;
    private $accountServices;
    private $locationServices;
    public int $ACCOUNT_ID = 0;
    public int $LOCATION_ID;
    public function boot(AccountJournalServices $accountJournalServices, AccountJournalEndingServices $accountJournalEndingServices, AccountServices $accountServices, LocationServices $locationServices)
    {
        $this->accountJournalServices       = $accountJournalServices;
        $this->accountJournalEndingServices = $accountJournalEndingServices;
        $this->accountServices              = $accountServices;
        $this->locationServices             = $locationServices;
    }
    public function mount(int $id, int $locationid)
    {
        $this->ACCOUNT_ID  = $id;
        $this->LOCATION_ID = $locationid;
        $this->dataList    = $this->accountJournalServices->getTransactionBalance($id, $locationid);

        $data = $this->accountServices->get($this->ACCOUNT_ID);
        if ($data) {
            $this->ACCOUNT_NAME = $data->NAME ?? '';

        }
        $loc = $this->locationServices->Get($this->LOCATION_ID);
        if ($loc) {
            $this->LOCATION_NAME = $loc->NAME ?? '';
        }
    }
    public function balanceUpdate()
    {
        $this->accountJournalEndingServices->ResetFirstEntryAccount($this->ACCOUNT_ID, $this->LOCATION_ID);
        session()->flash('request reset');
    }
    public function render()
    {
        return view('livewire.chart-of-account.chart-of-account-ending-balance');
    }
}
