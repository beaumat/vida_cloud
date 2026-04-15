<?php
namespace App\Livewire\WithHoldingTax;

use App\Services\AccountJournalServices;
use App\Services\BillingServices;
use App\Services\WithholdingTaxServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillList extends Component
{

    #[Reactive]
    public int $WITHHOLDING_TAX_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $EWT_RATE;

    public $dataList = [];
    private $withholdingTaxServices;
    private $billingServices;
    private $accountJournalServices;
    public function boot(WithholdingTaxServices $withholdingTaxServices, BillingServices $billingServices, AccountJournalServices $accountJournalServices)
    {
        $this->withholdingTaxServices = $withholdingTaxServices;
        $this->billingServices        = $billingServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function delete(int $ID, int $BILL_ID)
    {
        DB::beginTransaction();
        try {
            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->withholdingTaxServices->object_type_withholding_tax_id,
                    $this->WITHHOLDING_TAX_ID
                );
                $payData = $this->withholdingTaxServices->Get($this->WITHHOLDING_TAX_ID);
                if ($payData) {
                    $payBill = $this->withholdingTaxServices->GetWTaxBillExists($ID, $this->WITHHOLDING_TAX_ID, $BILL_ID);
                    if ($payBill) {
                        // ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $payBill->ACCOUNTS_PAYABLE_ID,
                            $payData->LOCATION_ID,
                            $JOURNAL_NO,
                            $BILL_ID,
                            $ID,
                            $this->withholdingTaxServices->object_type_witholding_tax_bills_id,
                            $payData->DATE,
                            1
                        );
                    }
                }
            }

            $this->withholdingTaxServices->DeleteBill($ID, $this->WITHHOLDING_TAX_ID);
            $this->billingServices->UpdateBalance($BILL_ID);
            $NEW_AMOUNT = $this->withholdingTaxServices->getTotal($this->WITHHOLDING_TAX_ID);
            $this->withholdingTaxServices->UpdateAMOUNT_WITHHELD($this->WITHHOLDING_TAX_ID, $NEW_AMOUNT);
            $this->dispatch('reload_bill');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    #[On('reload_bill')]
    public function render()
    {
        $this->dataList = $this->withholdingTaxServices->GetBillList($this->WITHHOLDING_TAX_ID);
        return view('livewire.with-holding-tax.bill-list');
    }
}
