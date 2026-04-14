<?php

namespace App\Livewire\BillCredit;

use App\Services\AccountServices;
use App\Services\BillCreditServices;
use App\Services\ClassServices;
use App\Services\ComputeServices;
use App\Services\TaxServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillCreditFormAccounts extends Component
{
    #[Reactive]
    public int $BILL_CREDIT_ID;
    #[Reactive]
    public int $TAX_ID;
    public int $ID;
    public int $LINE_NO;
    public int $ACCOUNT_ID;
    public int $AMOUNT;

    public int $TAXABLE;
    public float $TAXABLE_AMOUNT;
    public float $TAX_AMOUNT;
    public string $PARTICULARS;
    public int $CLASS_ID;
    public int $STATUS;
    public bool $openStatus;
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
    private $billCreditServices;
    private $accountServices;
    private $classServices;
    private $taxServices;
    private $computeServices;
    public function boot(
        BillCreditServices $billCreditServices,
        AccountServices $accountServices,
        ClassServices $classServices,
        TaxServices $taxServices,
        ComputeServices $computeServices
    ) {
        $this->billCreditServices = $billCreditServices;
        $this->accountServices = $accountServices;
        $this->classServices = $classServices;
        $this->taxServices = $taxServices;
        $this->computeServices = $computeServices;
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
                'ACCOUNT_ID' => 'required|not_in:0',
                'AMOUNT' => 'required|not_in:0'
            ],
            [],
            [
                'ACCOUNT_ID' => 'Account',
                'AMOUNT' => 'Amount'
            ]
        );
        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);

            $tax_result = $this->computeServices->ItemComputeTax($this->AMOUNT, $this->TAXABLE, $this->TAX_ID, $taxRate);

            if ($tax_result) {
                $this->TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $this->TAX_AMOUNT = $tax_result['TAX_AMOUNT'];
            }

            $this->billCreditServices->ExpenseStore(
                $this->BILL_CREDIT_ID,
                $this->ACCOUNT_ID,
                $this->AMOUNT,
                $this->TAXABLE,
                $this->TAXABLE_AMOUNT,
                $this->TAX_AMOUNT,
                $this->PARTICULARS,
                $this->CLASS_ID
            );


            $this->ACCOUNT_ID = 0;
            $this->AMOUNT = 0;
            $this->TAXABLE = false;
            $this->TAXABLE_AMOUNT = 0;
            $this->TAX_AMOUNT = 0;
            $this->PARTICULARS = '';
            $this->CLASS_ID = 0;
            $this->ACCOUNT_CODE = '';
            $this->ACCOUNT_DESCRIPTION = '';
            
            $getResult = $this->billCreditServices->ReComputed($this->BILL_CREDIT_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->updatedcodeBase();

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
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

        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->lineAmount, $this->lineTaxable, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->lineTaxableAmt = $tax_result['TAXABLE_AMOUNT'];
                $this->lineTaxAmount = $tax_result['TAX_AMOUNT'];
            }

            $this->billCreditServices->ExpenseUpdate(
                $id,
                $this->BILL_CREDIT_ID,
                $this->lineAmount,
                $this->lineTaxable,
                $this->lineTaxableAmt,
                $this->lineTaxAmount,
                $this->lineParticulars,
                $this->lineClassId
            );
            $getResult = $this->billCreditServices->ReComputed($this->BILL_CREDIT_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->cancelExpenses();


        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    public function deleteExpenses(int $id)
    {
        $this->billCreditServices->ExpenseDelete($id, $this->BILL_CREDIT_ID);
        $getResult = $this->billCreditServices->ReComputed($this->BILL_CREDIT_ID);
        $this->dispatch('update-amount', result: $getResult);
    }
    public function render()
    {
        $this->expenses = $this->billCreditServices->ExpenseView($this->BILL_CREDIT_ID);
        return view('livewire.bill-credit.bill-credit-form-accounts');
    }
}
