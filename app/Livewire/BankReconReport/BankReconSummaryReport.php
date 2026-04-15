<?php
namespace App\Livewire\BankReconReport;

use App\Services\BankReconServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconSummaryReport extends Component
{
    #[Reactive]
    public $BANK_RECON_ID;

    public float $BEGINNING_BALANCE = 0;
    public float $DEPOSIT_IN_TRANSIT = 0;
    public float $INTEREST_EARNED = 0;
    public float $BOOK_BALANCE = 0;
    public float $OUTSTANDING_CHECK = 0;
    public float $SERVICE_CHARGES = 0;
    public float $BANK_BALANCE = 0;
    public float $ENDING_BALANCE = 0;
    public float $DIFFERENCE = 0;
    public string $STATUS;
    private $bankReconServices;
    public function boot(BankReconServices $bankReconServices)
    {
        $this->bankReconServices = $bankReconServices;
    }
    public function mount()
    {
        $data = $this->bankReconServices->get($this->BANK_RECON_ID);
        if ($data) {
            $this->BEGINNING_BALANCE = $data->BEGINNING_BALANCE ?? 0;
            $this->ENDING_BALANCE    = $data->ENDING_BALANCE ?? 0;
            $this->SERVICE_CHARGES   = $data->SC_RATE ?? 0;
            $this->INTEREST_EARNED   = $data->IE_RATE ?? 0;

            $sumSource                = $this->bankReconServices->getSumDebitCredit($this->BANK_RECON_ID);
            $this->DEPOSIT_IN_TRANSIT = (float) $sumSource['DEBIT'];
            $this->OUTSTANDING_CHECK  = (float) $sumSource['CREDIT'];

            $balCharge = (float) $this->OUTSTANDING_CHECK - $this->DEPOSIT_IN_TRANSIT;
            $disTotal  = (float) ($this->BEGINNING_BALANCE - $balCharge);
            if ($this->ENDING_BALANCE == $disTotal) {
                $this->STATUS = "BALANCE";
            } else {
                $this->STATUS = "";
            }

        }
    }
    public function render()
    {
        return view('livewire.bank-recon-report.bank-recon-summary-report');
    }
}
