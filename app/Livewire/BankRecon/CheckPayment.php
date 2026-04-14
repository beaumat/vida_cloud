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

class CheckPayment extends Component
{
    #[Reactive]
    public int $ACCOUNT_RECONCILIATION_ID;
    #[Reactive]
    public int $ACCOUNT_ID;
    #[Reactive]
    public int $BANK_STATEMENT_ID;

    public int $LOCATION_ID;
    public int $BANK_STATEMENT_DETAILS_ID;
    public $locationList = [];
    public bool $showModal = false;
    public $search;
    public $dataList = [];
    public $dateList = [];

    private $bankReconServices;
    private $bankStatementServices;
    private $locationServices;
    private $userServices;
    public function boot(BankReconServices $bankReconServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices, BankStatementServices $bankStatementServices)
    {
        $this->bankReconServices     = $bankReconServices;
        $this->locationServices      = $locationServices;
        $this->userServices          = $userServices;
        $this->dateServices          = $dateServices;
        $this->bankStatementServices = $bankStatementServices;

    }
    public function mount()
    {
        $this->LOCATION_ID  = 0; //$this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    #[On('open-check')]
    public function openModal($result)
    {
        $this->getAllDate();
        $this->BANK_STATEMENT_DETAILS_ID = $result['ID'];
        $this->showModal                 = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function getAllDate()
    {
        $this->dateList = [];
        $data           = $this->bankStatementServices->listDetailsDateResult($this->BANK_STATEMENT_ID);
        foreach ($data as $item) {
            $this->dateList[] = $this->dateServices->DateFormatOnly($item["DATE_TRANSACTION"]);
        }

    }
    public function AddItem(int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT)
    {
        DB::beginTransaction();
        try {
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
            $this->dispatch('refresh-item');
            $this->dispatch('refresh-details');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash("error", $e->getMessage());
        }
    }
    #[On('refresh-item')]
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->bankReconServices->getPaymentList(
                $this->ACCOUNT_ID,
                $this->LOCATION_ID,
                1,
                $this->search,
                $this->dateList,
            );
        }
        return view('livewire.bank-recon.check-payment');
    }
}
