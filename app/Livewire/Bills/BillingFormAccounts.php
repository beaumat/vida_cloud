<?php
namespace App\Livewire\Bills;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\ClassServices;
use App\Services\ComputeServices;
use App\Services\TaxServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillingFormAccounts extends Component
{

    #[Reactive]
    public int $BILL_ID;
    #[Reactive]
    public int $TAX_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public string $DATE;
    #[Reactive]
    public int $STATUS;

    public int $ID;
    public int $LINE_NO;
    public int $ACCOUNT_ID;
    public float $AMOUNT;
    public bool $TAXABLE;
    public float $TAXABLE_AMOUNT;
    public float $TAX_AMOUNT;
    public string $PARTICULARS;
    public int $CLASS_ID;
    public int $openStatus = 0;
    public $expenses = [];
    public bool $codeBase = false;
    public $acctDescList = [];
    public $acctCodeList = [];
    public $classList = [];
    public string $ACCOUNT_CODE;
    public string $ACCOUNT_DESCRIPTION;
    public $saveSuccess;
    public $editExpensesId = null;
    public float $lineAmount;
    public bool $lineTaxable;
    public string $lineParticulars;
    public int $lineClassId;
    public float $lineTaxableAmt;
    public float $lineTaxAmount;
    private $billingServices;
    private $accountServices;
    private $classServices;
    private $taxServices;
    private $computeServices;
    private $accountJournalServices;
    public function boot(
        BillingServices $billingServices,
        AccountServices $accountServices,
        ClassServices $classServices,
        TaxServices $taxServices,
        ComputeServices $computeServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->billingServices        = $billingServices;
        $this->accountServices        = $accountServices;
        $this->classServices          = $classServices;
        $this->taxServices            = $taxServices;
        $this->computeServices        = $computeServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function updatedaccountid()
    {
        $acct = $this->accountServices->get($this->ACCOUNT_ID);

        if ($acct) {
            $this->ACCOUNT_CODE        = $acct->TAG ? $acct->TAG : '';
            $this->ACCOUNT_DESCRIPTION = $acct->NAME;
            $this->TAXABLE             = false;
            $this->PARTICULARS         = '';
        }
    }
    public function updatedcodebase()
    {
        if ($this->codeBase) {
            return $this->acctCodeList = $this->accountServices->getAccount(true);
        }
        return $this->acctDescList = $this->accountServices->getAccount(false);
    }

    public function mount()
    {

        $this->ACCOUNT_ID  = 0;
        $this->AMOUNT      = 0;
        $this->PARTICULARS = '';
        $this->TAXABLE     = false;
        $this->updatedcodeBase();
        $this->CLASS_ID  = 0;
        $this->classList = $this->classServices->GetList();
    }

    public function saveExpenses()
    {

        $this->validate(
            [
                'ACCOUNT_ID' => 'required|numeric|exists:account,id',
                'AMOUNT'     => 'required|numeric|not_in:0',
            ],
            [],
            [
                'ACCOUNT_ID' => 'Account',
                'AMOUNT'     => 'Amount',
            ]
        );

        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);

            $tax_result = $this->computeServices->ItemComputeTax(
                $this->AMOUNT,
                $this->TAXABLE,
                $this->TAX_ID,
                $taxRate
            );

            if ($tax_result) {
                $this->TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $this->TAX_AMOUNT     = $tax_result['TAX_AMOUNT'];
            }

            $this->billingServices->ExpenseStore(
                $this->BILL_ID,
                $this->ACCOUNT_ID,
                $this->AMOUNT,
                $this->TAXABLE,
                $this->TAXABLE_AMOUNT,
                $this->TAX_AMOUNT,
                $this->PARTICULARS,
                $this->CLASS_ID
            );
            $this->resetAccountEntry();
            $getResult = $this->billingServices->ReComputed($this->BILL_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->updatedcodeBase();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function resetAccountEntry()
    {
        $this->ACCOUNT_ID          = 0;
        $this->AMOUNT              = 0;
        $this->TAXABLE             = true;
        $this->TAXABLE_AMOUNT      = 0;
        $this->TAX_AMOUNT          = 0;
        $this->PARTICULARS         = '';
        $this->CLASS_ID            = 0;
        $this->ACCOUNT_CODE        = '';
        $this->ACCOUNT_DESCRIPTION = '';
    }
    public function editExpenses(int $lineId)
    {
        $data = $this->billingServices->ExpenseGet($lineId, $this->BILL_ID);
        if ($data) {
            $this->lineAmount      = $data->AMOUNT;
            $this->lineTaxable     = $data->TAXABLE;
            $this->lineParticulars = $data->PARTICULARS;
            $this->lineClassId     = $data->CLASS_ID ?? 0;
            $this->editExpensesId  = $data->ID;
        }
    }
    public function cancelExpenses()
    {
        $this->editExpensesId = null;
    }
    public function updateExpenses(int $id)
    {
        $this->validate(
            [
                'lineAmount' => 'required|not_in:0',
            ],
            [],
            [

                'lineAmount' => 'Amount',
            ]
        );
        DB::beginTransaction();
        try {
            $taxRate    = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->lineAmount, $this->lineTaxable, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->lineTaxableAmt = $tax_result['TAXABLE_AMOUNT'];
                $this->lineTaxAmount  = $tax_result['TAX_AMOUNT'];
            }

            $this->billingServices->ExpenseUpdate(
                $id,
                $this->BILL_ID,
                $this->lineAmount,
                $this->lineTaxable,
                $this->lineTaxableAmt,
                $this->lineTaxAmount,
                $this->lineParticulars,
                $this->lineClassId
            );

            $getResult = $this->billingServices->ReComputed($this->BILL_ID);
            DB::commit();
            $this->dispatch('update-amount', result: $getResult);
            $this->cancelExpenses();
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function deleteExpenses(int $Id)
    {
        DB::beginTransaction();
        try {
            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->billingServices->object_type_map_bill,
                    $this->BILL_ID
                );

                if ($JOURNAL_NO == 0) {
                    session()->flash('error', 'journal not found');
                    return;
                }

                $billData = $this->billingServices->get($this->BILL_ID);

                if ($billData) {
                    $billDataExpenses = $this->billingServices->ExpenseGet($Id, $this->BILL_ID, );
                    if ($billDataExpenses) {
                        // ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $billDataExpenses->ACCOUNT_ID,
                            $billData->LOCATION_ID,
                            $JOURNAL_NO,
                            $billDataExpenses->ACCOUNT_ID,
                            $Id,
                            $this->billingServices->object_type_map_bill_expenses,
                            $billData->DATE,
                            $billDataExpenses->AMOUNT > 0 ? 0 : 1,
                        );
                    }
                }
            }
            $this->billingServices->ExpenseDelete($Id, $this->BILL_ID);
            $getResult = $this->billingServices->ReComputed($this->BILL_ID);
            DB::commit();
            $this->dispatch('update-amount', result: $getResult);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $this->expenses = $this->billingServices->ExpenseView($this->BILL_ID);
        return view('livewire.bills.billing-form-accounts');
    }
}
