<?php

namespace App\Livewire\WriteCheck;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ClassServices;
use App\Services\ComputeServices;
use App\Services\TaxServices;
use App\Services\WriteCheckServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class WriteCheckFormAccounts extends Component
{

    #[Reactive]
    public int $CHECK_ID;
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
    public int $AMOUNT;
    public int $TAXABLE;
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
    private $writeCheckServices;
    private $accountServices;
    private $classServices;
    private $taxServices;
    private $computeServices;
    private $accountJournalServices;
    public function boot(
        WriteCheckServices $writeCheckServices,
        AccountServices $accountServices,
        ClassServices $classServices,
        TaxServices $taxServices,
        ComputeServices $computeServices,
        AccountJournalServices   $accountJournalServices
    ) {
        $this->writeCheckServices = $writeCheckServices;
        $this->accountServices = $accountServices;
        $this->classServices = $classServices;
        $this->taxServices = $taxServices;
        $this->computeServices = $computeServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function updatedaccountid()
    {
        $acct = $this->accountServices->get($this->ACCOUNT_ID);

        if ($acct) {
            $this->ACCOUNT_CODE = $acct->TAG ? $acct->TAG : '';
            $this->ACCOUNT_DESCRIPTION = $acct->NAME;
            $this->TAXABLE = false;
            $this->PARTICULARS = '';
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

        $this->ACCOUNT_ID = 0;
        $this->AMOUNT = 0;
        $this->PARTICULARS = '';
        $this->TAXABLE = false;
        $this->updatedcodeBase();
        $this->CLASS_ID = 0;
        $this->classList = $this->classServices->GetList();
    }


    public function saveExpenses()
    {

        $this->validate(
            [
                'ACCOUNT_ID'    =>  'required|not_in:0|exists:account,id',
                'AMOUNT'        =>  'required|not_in:0'
            ],
            [],
            [
                'ACCOUNT_ID'    => 'Account',
                'AMOUNT'        => 'Amount'
            ]
        );

        $recordExists = (bool) DB::table('check_expenses')
            ->where('CHECK_ID', '=', $this->CHECK_ID)
            ->where('ACCOUNT_ID', '=', $this->ACCOUNT_ID)
            ->exists();

        if ($recordExists) {
            session()->flash('error', 'Account already exists');
            return;
        }

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
                $this->TAX_AMOUNT = $tax_result['TAX_AMOUNT'];
            }

            $this->writeCheckServices->ExpenseStore(
                $this->CHECK_ID,
                $this->ACCOUNT_ID,
                $this->AMOUNT,
                $this->TAXABLE,
                $this->TAXABLE_AMOUNT,
                $this->TAX_AMOUNT,
                $this->PARTICULARS,
                $this->CLASS_ID
            );
            $this->resetAccountEntry();
            $getResult = $this->writeCheckServices->ReComputed($this->CHECK_ID);
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
        $this->ACCOUNT_ID = 0;
        $this->AMOUNT = 0;
        $this->TAXABLE = false;
        $this->TAXABLE_AMOUNT = 0;
        $this->TAX_AMOUNT = 0;
        $this->PARTICULARS = '';
        $this->CLASS_ID = 0;
        $this->ACCOUNT_CODE = '';
        $this->ACCOUNT_DESCRIPTION = '';
    }
    public function editExpenses(int $lineId, float $amount, bool $tax, string $particulars, int $Class_id)
    {
        $this->lineAmount = $amount;
        $this->lineTaxable = $tax;
        $this->lineParticulars = $particulars;
        $this->lineClassId = $Class_id;
        $this->editExpensesId = $lineId;
    }
    public function cancelExpenses()
    {
        $this->editExpensesId = null;
    }
    public function updateExpenses(int $id)
    {
        $this->validate(
            [
                'lineAmount' => 'required|not_in:0'
            ],
            [],
            [
                'lineAmount' => 'Amount'
            ]
        );
        DB::beginTransaction();
        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->lineAmount, $this->lineTaxable, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->lineTaxableAmt = $tax_result['TAXABLE_AMOUNT'];
                $this->lineTaxAmount = $tax_result['TAX_AMOUNT'];
            }

            $this->writeCheckServices->ExpenseUpdate(
                $id,
                $this->CHECK_ID,
                $this->lineAmount,
                $this->lineTaxable,
                $this->lineTaxableAmt,
                $this->lineTaxAmount,
                $this->lineParticulars,
                $this->lineClassId
            );

            $getResult = $this->writeCheckServices->ReComputed($this->CHECK_ID);
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
                    $this->writeCheckServices->object_type_check,
                    $this->CHECK_ID
                );

                if ($JOURNAL_NO  ==  0) {
                    session()->flash('error', 'journal not found');
                    return;
                }

                $checkData = $this->writeCheckServices->get($this->CHECK_ID);

                if ($checkData) {
                    $checkDataExpenses = $this->writeCheckServices->ExpenseGet($Id, $this->CHECK_ID,);
                    if ($checkDataExpenses) {
                        // ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $checkDataExpenses->ACCOUNT_ID,
                            $checkData->LOCATION_ID,
                            $JOURNAL_NO,
                            $checkDataExpenses->ACCOUNT_ID,
                            $Id,
                            $this->writeCheckServices->object_type_check_expenses,
                            $checkData->DATE,
                            $checkDataExpenses->AMOUNT > 0 ? 0 : 1,
                        );
                    }
                }
            }
            $this->writeCheckServices->ExpenseDelete($Id, $this->CHECK_ID);
            $getResult = $this->writeCheckServices->ReComputed($this->CHECK_ID);
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

        $this->expenses = $this->writeCheckServices->ExpenseView($this->CHECK_ID);
        return view('livewire.write-check.write-check-form-accounts');
    }
}
