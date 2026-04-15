<?php
namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use App\Services\BankStatementServices;
use App\Services\DateServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankStatement extends Component
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
    private $bankRecordServices;
    private $bankStatementServices;
    private $dateServices;
    public $dataList = [];


    public function boot(BankReconServices $bankRecordServices, BankStatementServices $bankStatementServices, DateServices $dateServices)
    {
        $this->bankRecordServices    = $bankRecordServices;
        $this->bankStatementServices = $bankStatementServices;
        $this->dateServices          = $dateServices;
    }
    public function LoadList()
    {
        $this->dataList = $this->bankStatementServices->getbankStatementRecon($this->BANK_STATEMENT_ID, $this->ACCOUNT_ID, $this->search);
    }

    public function autoMatch()
    {
        DB::beginTransaction();

        try {

            $this->dataList = $this->bankStatementServices->getbankStatementRecon($this->BANK_STATEMENT_ID, $this->ACCOUNT_ID, $this->search);

            foreach ($this->dataList as $row) {

                $dateEntry = $this->dateServices->DateFormatOnly($row->DATE_TRANSACTION);
                $AMOUNT           = (float) $row->DEBIT > 0 ? $row->DEBIT : $row->CREDIT;
                $result           = $this->bankRecordServices->getPayList($this->ACCOUNT_ID, $dateEntry, $AMOUNT);
                if ((bool) $result['IS_EXIST'] == true) {
                    $this->AddItem((int) $result['OBJECT_ID'], (int) $result['OBJECT_TYPE'], $result['OBJECT_DATE'], $result['ENTRY_TYPE'], $AMOUNT, $row->ID);
                }
            }
            DB::commit();
            $this->LoadList();
            session()->flash('message', 'Successfully matching entry ');
            $this->dispatch('total-summary');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            session()->flash("error", $e->getMessage());
        }

    }

    public function AddItem(int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT, int $BANK_STATEMENT_DETAILS_ID)
    {

    

        $this->bankRecordServices->ItemStore(
            $this->ACCOUNT_RECONCILIATION_ID,
            $OBJECT_ID,
            $OBJECT_TYPE,
            $OBJECT_DATE,
            $ENTRY_TYPE,
            $AMOUNT
        );

        $this->bankStatementServices->updateEntryBankStatement(
            $BANK_STATEMENT_DETAILS_ID,
            $OBJECT_TYPE,
            $OBJECT_ID);

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
        return view('livewire.bank-recon.bank-statement');
    }
}
