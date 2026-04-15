<?php
namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use App\Services\BankStatementServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class EntryList extends Component
{

    #[Reactive]
    public int $ACCOUNT_RECONCILIATION_ID;
    #[Reactive]
    public int $ACCOUNT_ID;
    #[Reactive]
    public int $BANK_STATEMENT_ID;
    public int $LOCATION_ID;
    public int $BANK_STATEMENT_DETAILS_ID;
    public float $AMOUNT = 0;
    public $locationList = [];
    public bool $showModal = false;
    public $search;
    public $dataList = [];
    public $dateEntry;
    private $bankReconServices;
    private $locationServices;
    private $userServices;
    private $bankStatementServices;
    private $dateServices;
    public function boot(BankReconServices $bankReconServices,
        LocationServices $locationServices,
        UserServices $userServices,
        BankStatementServices $bankStatementServices,
        DateServices $dateServices) {
        $this->bankReconServices     = $bankReconServices;
        $this->locationServices      = $locationServices;
        $this->userServices          = $userServices;
        $this->bankStatementServices = $bankStatementServices;
        $this->dateServices          = $dateServices;
    }
    public function mount()
    {
        $this->LOCATION_ID  = 0; // $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    #[On('open-entry')]
    public function openModal($result)
    {
        $this->dateEntry                 = $this->dateServices->DateFormatOnly($result['DATE']);
        $this->BANK_STATEMENT_DETAILS_ID = $result['ID'];
        $this->AMOUNT                    = (float) $result['AMOUNT'];
        $this->showModal                 = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function AddItem(int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT)
    {
        try {
            DB::beginTransaction();
            $this->bankReconServices->ItemStore(
                $this->ACCOUNT_RECONCILIATION_ID,
                $OBJECT_ID,
                $OBJECT_TYPE,
                $OBJECT_DATE,
                $ENTRY_TYPE,
                $AMOUNT
            );

            $this->bankStatementServices->updateEntryBankStatement(
                $this->BANK_STATEMENT_DETAILS_ID,
                $OBJECT_TYPE,
                $OBJECT_ID);

            DB::commit();
            $this->render();
            $this->dispatch('refresh-bank-statement');
            $this->dispatch('total-summary');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash("error", $e->getMessage());
        }
    }
    public function render()
    {

        if ($this->showModal) {

            $this->dataList = $this->bankReconServices->getPaymentList(
                $this->ACCOUNT_ID,
                $this->LOCATION_ID,
                $this->search,
                $this->dateEntry,
                $this->AMOUNT
            );
        }
        return view('livewire.bank-recon.entry-list');
    }
}
