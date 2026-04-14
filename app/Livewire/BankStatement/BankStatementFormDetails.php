<?php
namespace App\Livewire\BankStatement;

use App\Services\BankStatementServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankStatementFormDetails extends Component
{

    #[Reactive]
    public int $FILE_TYPE;

    #[Reactive]
    public int $BANK_STATEMENT_ID;

    public $dataList = [];

    private $bankStatementServices;
    public function boot(BankStatementServices $bankStatementServices): void
    {
        $this->bankStatementServices = $bankStatementServices;
    }

    public function render()
    {
        $this->dataList = $this->bankStatementServices->listDetails($this->BANK_STATEMENT_ID);

        return view('livewire.bank-statement.bank-statement-form-details');
    }
}
