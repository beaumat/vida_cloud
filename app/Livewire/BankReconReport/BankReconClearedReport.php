<?php
namespace App\Livewire\BankReconReport;

use App\Services\BankReconServices;
use App\Services\BankStatementServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconClearedReport extends Component
{

    #[Reactive]
    public $BANK_RECON_ID;
    private $bankReconServices;
    private $bankStatementServices;

    public $bankStatementList = [];
    public function boot(BankReconServices $bankReconServices, BankStatementServices $bankStatementServices)
    {
        $this->bankReconServices     = $bankReconServices;
        $this->bankStatementServices = $bankStatementServices;
    }
    public function mount()
    {
        $data = $this->bankReconServices->get($this->BANK_RECON_ID);
        if ($data) {
            $this->bankStatementList = $this->bankStatementServices->getbankStatement($data->BANK_STATEMENT_ID, "");
        }
    }
    public function render()
    {
        return view('livewire.bank-recon-report.bank-recon-cleared-report');
    }
}
