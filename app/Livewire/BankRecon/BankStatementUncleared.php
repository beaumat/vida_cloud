<?php
namespace App\Livewire\BankRecon;

use App\Services\BankStatementServices;
use App\Services\DateServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankStatementUncleared extends Component
{

    #[Reactive()]
    public int $ACCOUNT_RECONCILIATION_ID;
    #[Reactive()]
    public int $BANK_STATEMENT_ID;
    #[Reactive()]
    public int $ACCOUNT_ID;
    #[Reactive()]
    public int $STATUS;
    public $search;
    private $bankStatementServices;
    private $dateServices;
    public $dataList = [];

    public function boot(BankStatementServices $bankStatementServices, DateServices $dateServices)
    {
        $this->bankStatementServices = $bankStatementServices;
        $this->dateServices          = $dateServices;
    }
    public function LoadList()
    {
        $this->dataList = $this->bankStatementServices->getbankStatementReconUncleared($this->BANK_STATEMENT_ID, $this->ACCOUNT_ID,$this->search);
    }
   
    public function FindEntry(float $DEBIT, float $CREDIT, int $ID, string $DATE)
    {
        $result = [];
        if ($DEBIT == 0) {
            $result = ['ID' => $ID, 'AMOUNT' => $CREDIT,'DATE' => $DATE];

        } else {
            $result = ['ID' => $ID, 'AMOUNT' => $DEBIT,'DATE' => $DATE];
        }

        $this->dispatch('open-entry', result: $result);
    }
    #[On('refresh-bank-statement')]
    public function render()
    {
        $this->LoadList();
        return view('livewire.bank-recon.bank-statement-uncleared');
    }
}
