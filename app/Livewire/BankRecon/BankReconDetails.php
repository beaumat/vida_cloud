<?php
namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconDetails extends Component
{
    #[Reactive]
    public int $ACCOUNT_RECONCILIATION_ID;
    public float $BEGINNING_BALANCE;
    public float $CLEARED_DEPOSITS;
    public float $CLEARED_WITHDRAWALS;
    public float $CLEARED_BALANCE;
    public float $ENDING_BALANCE;
    public float $SC_RATE;
    public float $IE_RATE;
    public float $DIFFERENCE_BALANCE = 0;
    private $bankReconServices;
    public function boot(BankReconServices $bankReconServices)
    {
        $this->bankReconServices = $bankReconServices;
    }

    public function refresh()
    {
        $data = $this->bankReconServices->get($this->ACCOUNT_RECONCILIATION_ID);

        if ($data) {
            $this->BEGINNING_BALANCE   = $data->BEGINNING_BALANCE ?? 0;
            $this->ENDING_BALANCE      = $data->ENDING_BALANCE ?? 0;
            $this->CLEARED_DEPOSITS    = $data->CLEARED_DEPOSITS ?? 0;
            $this->CLEARED_WITHDRAWALS = $data->CLEARED_WITHDRAWALS ?? 0;
            $this->CLEARED_BALANCE     = $data->CLEARED_BALANCE ?? 0;
            $this->SC_RATE             = $data->SC_RATE ?? 0;
            $this->IE_RATE             = $data->IE_RATE ?? 0;
            $this->DIFFERENCE_BALANCE  = $this->ENDING_BALANCE - $this->CLEARED_BALANCE;

        }
    }
    #[On('refresh-details')]
    public function render()
    {
        $this->refresh();

        return view('livewire.bank-recon.bank-recon-details');
    }
}
