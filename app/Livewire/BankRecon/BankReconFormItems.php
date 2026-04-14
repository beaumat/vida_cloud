<?php
namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use App\Services\BankStatementServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconFormItems extends Component
{
    #[Reactive]
    public int $ACCOUNT_RECONCILIATION_ID;
    #[Reactive]
    public int $STATUS;
    public $dataList = [];
    public $search;
    private $bankReconServices;
    private $bankStatementServices;
    public function boot(BankReconServices $bankReconServices, BankStatementServices $bankStatementServices)
    {
        $this->bankReconServices     = $bankReconServices;
        $this->bankStatementServices = $bankStatementServices;
    }
    public function delete(int $ID)
    {

        DB::beginTransaction();
        try {
            //code...
            $result = $this->bankReconServices->GetItem($ID);
        
            if ($result) {
           
                $bsResult = $this->bankStatementServices->getDetails($result->OBJECT_DATE, $result->OBJECT_TYPE, $result->OBJECT_ID);
                if ($bsResult) {

                    $this->bankStatementServices->updateNullBankStatement($bsResult->ID);
                    $this->bankReconServices->ItemDelete($ID, $this->ACCOUNT_RECONCILIATION_ID);
                    DB::commit();
                }
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }

        // $this->dispatch('refresh-details');
                $this->dispatch('total-summary');
    }
    #[On('refresh-item')]
    public function render()
    {
        $this->dataList = $this->bankReconServices->ItemList($this->ACCOUNT_RECONCILIATION_ID, $this->search);

        return view('livewire.bank-recon.bank-recon-form-items');
    }
}
