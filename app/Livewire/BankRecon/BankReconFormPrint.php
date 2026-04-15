<?php
namespace App\Livewire\BankRecon;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Bank Reconciliation Report")]
class BankReconFormPrint extends Component
{
    public $ID;
    public function mount(int $id)
    {
        $this->ID = $id;
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.bank-recon.bank-recon-form-print');
    }
}
