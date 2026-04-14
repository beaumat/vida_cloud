<?php
namespace App\Livewire\BankStatement;

use App\Services\BankStatementServices;
use Livewire\Attributes\On;
use Livewire\Component;

class BankStatementPrint extends Component
{

    public $dataList = [];
    private $bankStatementServices;
    public function boot(BankStatementServices $bankStatementServices)
    {
        $this->bankStatementServices = $bankStatementServices;
    }
    public function mount($id)
    {
        $this->dataList = $this->bankStatementServices->listDetails($id);

        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.bank-statement.bank-statement-print');
    }
}
