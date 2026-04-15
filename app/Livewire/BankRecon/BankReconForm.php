<?php
namespace App\Livewire\BankRecon;

use App\Services\AccountServices;
use App\Services\BankReconServices;
use App\Services\BankStatementServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bank Reconciliation')]
class BankReconForm extends Component
{
    public string $STATUS_DESCRIPTION;
    public int $openStatus = 0;
    public int $ID;
    public $DATE;
    public string $CODE;
    public int $ACCOUNT_ID;
    public int $LOCATION_ID;
    public int $PREVIOUS_ID;
    public int $SEQUENCE_NO;
    public float $BEGINNING_BALANCE;
    public float $CLEARED_DEPOSITS;
    public float $CLEARED_WITHDRAWALS;
    public float $CLEARED_BALANCE;
    public float $ENDING_BALANCE;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DATE;

    public float $BANK_DEBIT;
    public float $BANK_CREDIT;
    public float $CLEARED_DEBIT;
    public float $CLEARED_CREDIT;

    public int $BANK_STATEMENT_ID;
    public int $SC_ACCOUNT_ID;
    public int $IE_ACCOUNT_ID;
    public float $SC_RATE;
    public float $IE_RATE;
    public $SC_DATE;
    public $IE_DATE;
    public $sc_accountList        = [];
    public $ie_accountList        = [];
    public $bankStatementList     = [];
    public $accountList           = [];
    public $locationList          = [];
    public bool $Modify           = true;
    public bool $bankStateRefresh = false;
    private $bankReconServices;
    private $userServices;
    private $locationServices;
    private $accountServices;
    private $bankStatementServices;
    public function boot(
        BankReconServices $bankReconServices,
        UserServices $userServices,
        LocationServices $locationServices,
        AccountServices $accountServices,
        BankStatementServices $bankStatementServices
    ) {
        $this->bankReconServices     = $bankReconServices;
        $this->userServices          = $userServices;
        $this->accountServices       = $accountServices;
        $this->locationServices      = $locationServices;
        $this->bankStatementServices = $bankStatementServices;
    }
    #[On('total-summary')]
    public function getMatchEntry()
    {
        $resultA = $this->bankReconServices->getSumDebitCredit($this->ID);
        $resultB = $this->bankStatementServices->getSumDebitCredit($this->BANK_STATEMENT_ID);

        $this->BANK_DEBIT  = $resultB['DEBIT'];
        $this->BANK_CREDIT = $resultB['CREDIT'];

        $this->CLEARED_DEBIT  = $resultA['DEBIT'];
        $this->CLEARED_CREDIT = $resultA['CREDIT'];

    }
    public function getBankStateRefresh()
    {
        $this->bankStateRefresh = $this->bankStateRefresh ? false : true;
    }
    public string $tab = "bank";
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }

    private function dropDownLoad()
    {
        $this->accountList    = $this->accountServices->getBankAccount();
        $this->locationList   = $this->locationServices->getList();
        $this->sc_accountList = $this->accountServices->getExpenses();
        $this->ie_accountList = $this->accountServices->getIncome();

    }
    private function SelectBank()
    {
        $this->bankStatementList = $this->bankStatementServices->getList($this->ACCOUNT_ID);
    }
    public function updatedaccountid()
    {
        $this->SelectBank();
        $this->getBankStateRefresh();
    }
    public function updatedBankStatementId()
    {
        $data = $this->bankStatementServices->get($this->BANK_STATEMENT_ID);
        if ($data) {
            $this->BEGINNING_BALANCE = $data->BEGINNING_BALANCE ?? 0;
            $this->ENDING_BALANCE    = $data->ENDING_BALANCE ?? 0;
            $this->DATE              = $data->DATE_TO;
        }
    }

    public function openSalesCollection()
    {
        $this->dispatch('open-collection');
    }
    public function openCheckPayment()
    {
        $this->dispatch('open-check');
    }
    private function getInfo($data)
    {
        $this->ID                  = $data->ID;
        $this->DATE                = $data->DATE;
        $this->CODE                = $data->CODE ?? 0;
        $this->ACCOUNT_ID          = $data->ACCOUNT_ID ?? 0;
        $this->LOCATION_ID         = $data->LOCATION_ID;
        $this->PREVIOUS_ID         = $data->PREVIOUS_ID ?? 0;
        $this->SEQUENCE_NO         = $data->SEQUENCE_NO ?? 0;
        $this->BEGINNING_BALANCE   = $data->BEGINNING_BALANCE ?? 0;
        $this->CLEARED_DEPOSITS    = $data->CLEARED_DEPOSITS ?? 0;
        $this->CLEARED_WITHDRAWALS = $data->CLEARED_WITHDRAWALS ?? 0;
        $this->CLEARED_BALANCE     = $data->CLEARED_BALANCE ?? 0;
        $this->ENDING_BALANCE      = $data->ENDING_BALANCE ?? 0;
        $this->NOTES               = $data->NOTES ?? '';
        $this->STATUS              = $data->STATUS ?? 0;
        $this->SC_DATE             = $data->SC_DATE ?? null;
        $this->IE_DATE             = $data->IE_DATE ?? null;
        $this->SC_RATE             = $data->SC_RATE ?? 0;
        $this->SC_ACCOUNT_ID       = $data->SC_ACCOUNT_ID ?? 0;
        $this->IE_ACCOUNT_ID       = $data->IE_ACCOUNT_ID ?? 0;
        $this->IE_RATE             = $data->IE_RATE ?? 0;
        $this->updatedaccountid();
        $this->BANK_STATEMENT_ID = $data->BANK_STATEMENT_ID ?? 0;

        $this->getMatchEntry();
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->bankReconServices->get($id);
            if ($data) {
                $this->dropDownLoad();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingbank_recon')->with('error', $errorMessage);
        }

        $this->dropDownLoad();
        $this->ID                  = 0;
        $this->DATE                = null;
        $this->CODE                = '';
        $this->ACCOUNT_ID          = 0;
        $this->LOCATION_ID         = $this->userServices->getLocationDefault();
        $this->PREVIOUS_ID         = 0;
        $this->SEQUENCE_NO         = 0;
        $this->BEGINNING_BALANCE   = 0;
        $this->CLEARED_DEPOSITS    = 0;
        $this->CLEARED_WITHDRAWALS = 0;
        $this->CLEARED_BALANCE     = 0;
        $this->ENDING_BALANCE      = 0;
        $this->NOTES               = '';
        $this->STATUS              = 0;
        $this->Modify              = true;

        $this->SC_RATE       = 0;
        $this->SC_ACCOUNT_ID = 0;
        $this->SC_DATE       = null;

        $this->IE_ACCOUNT_ID     = 0;
        $this->IE_RATE           = 0;
        $this->IE_DATE           = null;
        $this->BANK_STATEMENT_ID = 0;
    }
    public function save()
    {

        $this->validate(
            [
                'ACCOUNT_ID'        => 'required|not_in:0|exists:account,id',
                'CODE'              => $this->ID > 0 ? 'required|max:20|unique:account_reconciliation,code,' . $this->ID : 'nullable',
                'DATE'              => 'required|date',
                'LOCATION_ID'       => 'required|numeric|exists:location,id',
                'SC_RATE'           => 'required|numeric|min:0',
                'IE_RATE'           => 'required|numeric|min:0',
                'SC_ACCOUNT_ID'     => $this->SC_RATE > 0 ? 'required|numeric|exists:account,id' : 'nullable',
                'IE_ACCOUNT_ID'     => $this->IE_RATE > 0 ? 'required|numeric|exists:account,id' : 'nullable',
                'SC_DATE'           => $this->SC_RATE > 0 ? 'required|date' : 'nullable',
                'IE_DATE'           => $this->IE_RATE > 0 ? 'required|date' : 'nullable',
                'BEGINNING_BALANCE' => 'required|numeric',
                'ENDING_BALANCE'    => 'required|numeric|min:1',
                'BANK_STATEMENT_ID' => 'required|numeric|exists:bank_statement,id',
            ],
            [],
            [
                'ACCOUNT_ID'        => 'Bank Account',
                'CODE'              => 'Reference No.',
                'DATE'              => 'Bank Statement Date',
                'LOCATION_ID'       => 'Location',
                'SC_ACCOUNT_ID'     => 'Service Charge Account',
                'IE_ACCOUNT_ID'     => 'Interest Earn Account',
                'SC_DATE'           => 'Service Charge Date',
                'IE_DATE'           => 'Interest Earn Date',
                'BEGINNING_BALANCE' => 'Beginning Balance',
                'ENDING_BALANCE'    => 'Ending Balance',
                'BANK_STATEMENT_ID' => 'Bank Statement',
            ]
        );

        DB::beginTransaction();
        try {

            if ($this->ID == 0) {
                $this->ID = $this->bankReconServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->ACCOUNT_ID,
                    $this->LOCATION_ID,
                    $this->PREVIOUS_ID,
                    $this->SEQUENCE_NO,
                    $this->BEGINNING_BALANCE,
                    $this->CLEARED_DEPOSITS,
                    $this->CLEARED_WITHDRAWALS,
                    $this->CLEARED_BALANCE,
                    $this->ENDING_BALANCE,
                    $this->NOTES,
                    $this->SC_ACCOUNT_ID,
                    $this->SC_RATE,
                    $this->IE_ACCOUNT_ID,
                    $this->IE_RATE,
                    $this->SC_DATE,
                    $this->IE_DATE,
                    $this->BANK_STATEMENT_ID
                );
                $this->bankReconServices->Recomputed($this->ID);
                DB::commit();
                return Redirect::route('bankingbank_recon_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->bankReconServices->Update(
                    $this->ID,
                    $this->DATE,
                    $this->CODE,
                    $this->NOTES,
                    $this->SC_ACCOUNT_ID,
                    $this->SC_RATE,
                    $this->IE_ACCOUNT_ID,
                    $this->IE_RATE,
                    $this->SC_DATE,
                    $this->IE_DATE,
                    $this->BANK_STATEMENT_ID
                );
                $this->bankReconServices->Recomputed($this->ID);
                DB::commit();
                session()->flash('message', 'Successfully updated');
                $this->Modify = false;
            }
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updateCancel()
    {
        return Redirect::route('bankingbank_recon_edit', ['id' => $this->ID]);
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function getPosted()
    {

        $this->bankReconServices->StatusUpdate($this->ID, 15);
        $data = $this->bankReconServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
            $this->Modify = false;
            session()->flash('message', 'Successfully posted');
            return;
        }
    }
    public function render()
    {
        return view('livewire.bank-recon.bank-recon-form');
    }
}
