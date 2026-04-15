<?php
namespace App\Livewire\BillCredit;

use App\Services\AccountJournalServices;
use App\Services\BillCreditServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\DocumentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bill Credits')]
class BillCreditForm extends Component
{

    public int $ID;
    public int $VENDOR_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ACCOUNTS_PAYABLE_ID;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;
    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public $vendorList   = [];
    public $locationList = [];
    public $taxList      = [];
    public bool $Modify;
    private $billCreditServices;
    private $locationServices;
    private $contactServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $objectServices;
    private $documentTypeServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    public string $tab = 'item';
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function boot(
        BillCreditServices $billCreditServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        ObjectServices $objectServices,
        DocumentTypeServices $documentTypeServices,
        ItemInventoryServices $itemInventoryServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->billCreditServices     = $billCreditServices;
        $this->locationServices       = $locationServices;
        $this->contactServices        = $contactServices;
        $this->taxServices            = $taxServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices  = $systemSettingServices;

        $this->documentTypeServices   = $documentTypeServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->objectServices         = $objectServices;
    }
    public function LoadDropdown()
    {
        $this->vendorList   = $this->contactServices->getVendorDoc();
        $this->locationList = $this->locationServices->getList();
        $this->taxList      = $this->taxServices->getList();
    }
    public function getTax()
    {
        $tax = $this->taxServices->get($this->INPUT_TAX_ID);
        if ($tax) {
            $this->INPUT_TAX_RATE       = (float) $tax->INPUT_TAX_RATE;
            $this->INPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->INPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }

    private function getInfo($data)
    {
        $this->ID                   = $data->ID;
        $this->CODE                 = $data->CODE;
        $this->DATE                 = $data->DATE;
        $this->LOCATION_ID          = $data->LOCATION_ID;
        $this->VENDOR_ID            = $data->VENDOR_ID;
        $this->NOTES                = $data->NOTES;
        $this->AMOUNT               = $data->AMOUNT;
        $this->AMOUNT_APPLIED       = $data->AMOUNT_APPLIED ?? 0;
        $this->STATUS               = $data->STATUS;
        $this->INPUT_TAX_ID         = $data->INPUT_TAX_ID > 0 ? $data->INPUT_TAX_ID : 0;
        $this->INPUT_TAX_RATE       = $data->INPUT_TAX_RATE > 0 ? $data->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT     = $data->INPUT_TAX_AMOUNT > 0 ? $data->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $data->INPUT_TAX_VAT_METHOD > 0 ? $data->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $data->INPUT_TAX_ACCOUNT_ID > 0 ? $data->INPUT_TAX_ACCOUNT_ID : 0;
        $this->STATUS_DESCRIPTION   = $this->documentStatusServices->getDesc($this->STATUS);
        $this->ACCOUNTS_PAYABLE_ID  = $data->ACCOUNTS_PAYABLE_ID;

        if ($this->billCreditServices->isItemTab($data->ID)) {
            $this->tab = "item";
            return;
        }
        $this->tab = "account";
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->billCreditServices->get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);

                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorsbills')->with('error', $errorMessage);
        }
        $this->LoadDropdown();
        $this->tab                  = "item";
        $this->Modify               = true;
        $this->ID                   = 0;
        $this->CODE                 = '';
        $this->DATE                 = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID          = $this->userServices->getLocationDefault();
        $this->VENDOR_ID            = 0;
        $this->NOTES                = '';
        $this->AMOUNT               = 0;
        $this->AMOUNT_APPLIED       = 0;
        $this->STATUS               = 0;
        $this->INPUT_TAX_ID         = (int) $this->systemSettingServices->GetValue('InputTaxId');
        $this->INPUT_TAX_RATE       = 0;
        $this->INPUT_TAX_AMOUNT     = 0;
        $this->INPUT_TAX_VAT_METHOD = 0;
        $this->INPUT_TAX_ACCOUNT_ID = 0;
        $this->STATUS_DESCRIPTION   = "";
        $this->ACCOUNTS_PAYABLE_ID  = 21;
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
                        'VENDOR_ID'    => 'required|not_in:0',
                        'INPUT_TAX_ID' => 'required|not_in:0',
                        'DATE'         => 'required',
                        'LOCATION_ID'  => 'required',

                    ],
                    [],
                    [
                        'VENDOR_ID'    => 'Vendor',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE'         => 'Date',
                        'LOCATION_ID'  => 'Location',

                    ]
                );
                if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                    session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                    return;
                }

                $this->getTax();
                $this->ID = $this->billCreditServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID

                );

                return Redirect::route('vendorsbill_credit_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->validate(
                    [
                        'VENDOR_ID'    => 'required|not_in:0',
                        'CODE'         => 'required|max:20|unique:purchase_order,code,' . $this->ID,
                        'INPUT_TAX_ID' => 'required|not_in:0',
                        'DATE'         => 'required',
                        'LOCATION_ID'  => 'required',

                    ],
                    [],
                    [
                        'VENDOR_ID'    => 'Vendor',
                        'CODE'         => 'Reference No.',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE'         => 'Date',
                        'LOCATION_ID'  => 'Location',

                    ]
                );

                $this->getTax();
                $this->billCreditServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID
                );
                $this->billCreditServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                $getResult = $this->billCreditServices->ReComputed($this->ID);
                $this->getUpdateAmount($getResult);
                session()->flash('message', 'Successfully updated');
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
            $this->AMOUNT           = $list['AMOUNT'];
            $this->INPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
        }
        $this->AMOUNT_APPLIED = 0;
    }
    public function updateCancel()
    {
        $BILL = $this->billCreditServices->get($this->ID);
        if ($BILL) {
            $this->getInfo($BILL);
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

    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->documentTypeServices->getId('Bill Credit');
            $data            = $this->billCreditServices->ItemInventory($this->ID);
            if ($data) {
                $this->itemInventoryServices->InventoryExecute($data, $this->LOCATION_ID, $SOURCE_REF_TYPE, $this->DATE, false);
            }
            return true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    private function AccountJournal(): bool
    {
        try {

            $billCredits        = (int) $this->objectServices->ObjectTypeID('BILL_CREDIT');
            $billCreditItems    = (int) $this->objectServices->ObjectTypeID('BILL_CREDIT_ITEMS');
            $billCreditExpenses = (int) $this->objectServices->ObjectTypeID('BILL_CREDIT_EXPENSES');

            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($billCredits, $this->ID) + 1;
            //Main
            $billCreditData = $this->billCreditServices->getBillCreditJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditData, $this->LOCATION_ID, $billCredits, $this->DATE, "AP");
            //Tax
            $billDataTax = $this->billCreditServices->getBillCreditTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billDataTax, $this->LOCATION_ID, $billCredits, $this->DATE, "TAX");

            //Item
            $billCreditItemData = $this->billCreditServices->getBillCreditItemJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditItemData, $this->LOCATION_ID, $billCreditItems, $this->DATE, "ASSET");
            //Expenses
            $billCreditExpensesData = $this->billCreditServices->getBillCreditExpenseJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditExpensesData, $this->LOCATION_ID, $billCreditExpenses, $this->DATE, "EXPENSE");

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                return true;
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            return false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function getPosted()
    {
        try {

            $count_item    = (int) $this->billCreditServices->CountItems($this->ID, true);
            $count_expense = (int) $this->billCreditServices->CountItems($this->ID, false);
            $count         = $count_item + $count_expense;
            if ($count == 0) {
                session()->flash('error', 'Item not found.');
                return;
            }
            DB::beginTransaction();
            if (! $this->ItemInventory()) {
                DB::rollBack();
                return;
            }

            if (! $this->AccountJournal()) {
                DB::rollBack();
                return;
            }

            $this->billCreditServices->StatusUpdate($this->ID, 15);
            DB::commit();
            $data = $this->billCreditServices->get($this->ID);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            session()->flash('message', 'Successfully posted');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.bill-credit.bill-credit-form');
    }
}
