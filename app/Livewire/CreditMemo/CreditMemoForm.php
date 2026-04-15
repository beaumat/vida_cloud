<?php

namespace App\Livewire\CreditMemo;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\CreditMemoServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Credit Memo')]
class CreditMemoForm extends Component
{

    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $CUSTOMER_ID;
    public int $LOCATION_ID;
    public int $CLASS_ID;
    public int $SALES_REP_ID;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public string $NOTES;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public int $OUTPUT_TAX_ID;
    public float $OUTPUT_TAX_RATE;
    public float $OUTPUT_TAX_AMOUNT;
    public float $OUTPUT_TAX_VAT_METHOD;
    public float $OUTPUT_TAX_ACCOUNT_ID;
    public float $TAXABLE_AMOUNT;
    public float $NONTAXABLE_AMOUNT;
    public int $STATUS;
    public int $STATUS_DATE;
    public string $STATUS_DESCRIPTION;
    public $contactList = [];
    public $locationList = [];
    public $taxList = [];
    public bool $Modify;
    private $locationServices;
    private $contactServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $scheduleServices;
    private $creditMemoServices;

    public string $tab = "item";
    public function SelectTab(string $select)
    {
        $this->tab = $select;
    }
    public function boot(
        CreditMemoServices $creditMemoServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
    ) {
        $this->creditMemoServices = $creditMemoServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->taxServices = $taxServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->accountServices = $accountServices;
    }

    public function LoadDropdown()
    {
        $this->contactList = $this->contactServices->getCustoPatientList();
        $this->locationList = $this->locationServices->getList();
        $this->taxList = $this->taxServices->getList();
    }
    public function getTax()
    {
        $tax = $this->taxServices->get($this->OUTPUT_TAX_ID);
        if ($tax) {
            $this->OUTPUT_TAX_RATE = (float) $tax->OUTPUT_TAX_RATE;
            $this->OUTPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->OUTPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }

    private function getInfo($Data)
    {
        $this->ID = $Data->ID;
        $this->CODE = $Data->CODE;
        $this->DATE = $Data->DATE;
        $this->LOCATION_ID = $Data->LOCATION_ID;
        $this->CUSTOMER_ID = $Data->CUSTOMER_ID;
        $this->SALES_REP_ID = $Data->SALES_REP_ID ?? 0;
        $this->CLASS_ID = $Data->CLASS_ID ? $Data->CLASS_ID : 0;
        $this->NOTES = $Data->NOTES ?? '';
        $this->AMOUNT = $Data->AMOUNT;
        $this->AMOUNT_APPLIED = $Data->AMOUNT_APPLIED;
        $this->ACCOUNTS_RECEIVABLE_ID = $Data->ACCOUNTS_RECEIVABLE_ID;
        $this->STATUS = $Data->STATUS;
        $this->OUTPUT_TAX_ID = $Data->OUTPUT_TAX_ID ? $Data->OUTPUT_TAX_ID : 0;
        $this->OUTPUT_TAX_RATE = $Data->OUTPUT_TAX_RATE ? $Data->OUTPUT_TAX_RATE : 0;
        $this->OUTPUT_TAX_AMOUNT = $Data->OUTPUT_TAX_AMOUNT ? $Data->OUTPUT_TAX_AMOUNT : 0;
        $this->OUTPUT_TAX_VAT_METHOD = $Data->OUTPUT_TAX_VAT_METHOD ? $Data->OUTPUT_TAX_VAT_METHOD : 0;
        $this->OUTPUT_TAX_ACCOUNT_ID = $Data->OUTPUT_TAX_ACCOUNT_ID ? $Data->OUTPUT_TAX_ACCOUNT_ID : 0;
        $this->TAXABLE_AMOUNT = $Data->TAXABLE_AMOUNT ? $Data->TAXABLE_AMOUNT : 0;
        $this->NONTAXABLE_AMOUNT = $Data->NONTAXABLE_AMOUNT ? $Data->NONTAXABLE_AMOUNT : 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }


    public function mount($id = null)
    {


        if (is_numeric($id)) {
            $this->LoadDropdown();
            $data = $this->creditMemoServices->get($id);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('customerscredit_memo')->with('error', $errorMessage);
        }
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->LoadDropdown();
        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->CUSTOMER_ID = 0;
        $this->SALES_REP_ID = 0;
        $this->CLASS_ID = 0;
        $this->NOTES = '';
        $this->AMOUNT = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivables');
        $this->STATUS = 0;
        $this->OUTPUT_TAX_ID = (int) $this->systemSettingServices->GetValue('OutputTaxId');
        $this->OUTPUT_TAX_RATE = 0;
        $this->OUTPUT_TAX_AMOUNT = 0;
        $this->OUTPUT_TAX_VAT_METHOD = 0;
        $this->OUTPUT_TAX_ACCOUNT_ID = 0;
        $this->TAXABLE_AMOUNT = 0;
        $this->NONTAXABLE_AMOUNT = 0;
        $this->STATUS_DESCRIPTION = "";


        $this->getTax();
    }
    public function getModify()
    {
        $this->Modify = true;
    }

    public function save()
    {
        try {
            if ($this->ID == 0) {

                $this->validate(
                    [
                        'CUSTOMER_ID' => 'required|not_in:0',
                        'OUTPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required'
                    ],
                    [],
                    [
                        'CUSTOMER_ID' => 'Customer',
                        'OUTPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',

                    ]
                );

                if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                    session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                    return;

                }


                $this->getTax();

                $this->ID = (int) $this->creditMemoServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_AMOUNT,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID
                );
                return Redirect::route('customerscredit_memo_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                $this->validate(
                    [
                        'CUSTOMER_ID' => 'required|not_in:0',
                        'CODE' => 'required|max:20|unique:credit_memo,code,' . $this->ID,
                        'OUTPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                    ],
                    [],
                    [
                        'CUSTOMER_ID' => 'Customer',
                        'CODE' => 'Reference No.',
                        'OUTPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',

                    ]
                );

                $this->getTax();

                $this->creditMemoServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_AMOUNT,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID
                );

                $this->creditMemoServices->getUpdateTaxItem($this->ID, $this->OUTPUT_TAX_ID);

                $getResult = $this->creditMemoServices->ReComputed($this->ID);

                $this->getUpdateAmount($getResult);

                session()->flash('message', 'Successfully updated');
            }

            $data = $this->creditMemoServices->get($this->ID);

            if ($data) {
                $this->getInfo($data);
            }

            $this->Modify = false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT = $list['AMOUNT'];
            $this->AMOUNT_APPLIED = $list['AMOUNT_APPLIED'];
            $this->OUTPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
            $this->TAXABLE_AMOUNT = $list['TAXABLE_AMOUNT'];
            $this->NONTAXABLE_AMOUNT = $list['NONTAXABLE_AMOUNT'];
        }
    }
    #[On('update-status')]
    public function updateStatus()
    {
        $data = $this->creditMemoServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function updateCancel()
    {
        $data = $this->creditMemoServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
        $this->Modify = false;
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
        return view('livewire.credit-memo.credit-memo-form');
    }
}
