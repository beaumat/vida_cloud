<?php
namespace App\Livewire\BankReconReport;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconFooterReport extends Component
{
    #[Reactive]
    public $BANK_RECON_ID;
    public function render()
    {
        return view('livewire.bank-recon-report.bank-recon-footer-report');
    }
}
