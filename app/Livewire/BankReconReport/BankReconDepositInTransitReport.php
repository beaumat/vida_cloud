<?php
namespace App\Livewire\BankReconReport;

use App\Services\BankReconServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconDepositInTransitReport extends Component
{
    #[Reactive]
    public $BANK_RECON_ID;

    public $dataList = [];
    private $bankReconServices;
    public function boot(BankReconServices $bankReconServices)
    {
              $this->bankReconServices = $bankReconServices;
    }
    public function mount()
    {
        $this->dataList = $this->bankReconServices->ItemListByEntry($this->BANK_RECON_ID, 0);
    }
    public function render()
    {   


        return view('livewire.bank-recon-report.bank-recon-deposit-in-transit-report');
    }
}
