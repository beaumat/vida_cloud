<?php
namespace App\Livewire\WriteCheck;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use App\Services\WriteCheckServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pay by Check')]
class WriteCheckForm extends Component
{

    public float $TOTAL_ITEMS    = 0;
    public float $TOTAL_EXPENSES = 0;
    public int $ID;
    public string $CODE;
    public bool $UNPOSTED = true;
    public $DATE;
    public int $PAY_TO_ID;
    public int $LOCATION_ID;
    public int $BANK_ACCOUNT_ID;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;
    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public string $NOTES;
    public int $TYPE   = 0;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION;
    public int $ACCOUNTS_PAYABLE_ID = 21;
    public $locationList            = [];
    public $taxList                 = [];
    public bool $Modify;
    public $contactList = [];
    public $accountList = [];
    private $writeCheckServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $accountServices;
    private $documentStatusServices;
    private $taxServices;
    private $accountJournalServices;
    private $systemSettingServices;
    private $itemInventoryServices;
    public function boot(
        WriteCheckServices $writeCheckServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountServices $accountServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices,
        TaxServices $taxServices,
        SystemSettingServices $systemSettingServices,
        ItemInventoryServices $itemInventoryServices
    ) {
        $this->writeCheckServices     = $writeCheckServices;
        $this->contactServices        = $contactServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->accountServices        = $accountServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->taxServices            = $taxServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->itemInventoryServices  = $itemInventoryServices;
    }
    public string $tab = 'item';
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function getTax()
    {
        $tax = $this->taxServices->get($this->INPUT_TAX_ID ?? 0);
        if ($tax) {
            $this->INPUT_TAX_RATE       = (float) $tax->INPUT_TAX_RATE;
            $this->INPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->INPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }
    private function LoadDropDown()
    {
        $this->contactList  = $this->contactServices->getListAllType();
        $this->locationList = $this->locationServices->getList();
        $this->accountList  = $this->accountServices->getBankAccount();
        $this->taxList      = $this->taxServices->getList();
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->writeCheckServices->Get($id);
            if ($data) {
                $this->LoadDropDown();
                $this->getInfo($data);

                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingmake_cheque')->with('error', $errorMessage);
        }
        $this->LoadDropDown();
        $this->ID                   = 0;
        $this->CODE                 = '';
        $this->DATE                 = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID          = $this->userServices->getLocationDefault();
        $this->AMOUNT               = 0;
        $this->TOTAL_EXPENSES       = 0;
        $this->TOTAL_ITEMS          = 0;
        $this->NOTES                = '';
        $this->BANK_ACCOUNT_ID      = 0;
        $this->PAY_TO_ID            = 0;
        $this->Modify               = true;
        $this->AMOUNT_APPLIED       = 0;
        $this->INPUT_TAX_ID         = (int) $this->systemSettingServices->GetValue('InputTaxId');
        $this->INPUT_TAX_RATE       = 0;
        $this->INPUT_TAX_AMOUNT     = 0;
        $this->INPUT_TAX_VAT_METHOD = 0;
        $this->INPUT_TAX_ACCOUNT_ID = 0;
        $this->getTax();
    }
    public function getInfo($data)
    {
        $this->ID                   = $data->ID;
        $this->CODE                 = $data->CODE;
        $this->DATE                 = $data->DATE;
        $this->LOCATION_ID          = $data->LOCATION_ID;
        $this->AMOUNT               = $data->AMOUNT;
        $this->NOTES                = $data->NOTES ?? '';
        $this->BANK_ACCOUNT_ID      = $data->BANK_ACCOUNT_ID;
        $this->PAY_TO_ID            = $data->PAY_TO_ID;
        $this->INPUT_TAX_ID         = $data->INPUT_TAX_ID ?? 0;
        $this->INPUT_TAX_RATE       = $data->INPUT_TAX_RATE > 0 ? $data->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT     = $data->INPUT_TAX_AMOUNT > 0 ? $data->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $data->INPUT_TAX_VAT_METHOD > 0 ? $data->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $data->INPUT_TAX_ACCOUNT_ID > 0 ? $data->INPUT_TAX_ACCOUNT_ID : 0;
        $this->STATUS               = $data->STATUS;
        if ($this->STATUS == 16) {
            $this->removeJournal();
        }
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        $this->Modify             = false;
        $this->getTax();
        $getResult = $this->writeCheckServices->ReComputed($this->ID);
        $this->getUpdateAmount($getResult);

        if ($this->writeCheckServices->isItemTab($data->ID)) {
            $this->tab = "item";
            return;
        }
        $this->tab = "account";
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function updateCancel()
    {
        $data = $this->writeCheckServices->Get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function save()
    {

        $this->validate(
            [
                'BANK_ACCOUNT_ID' => 'required|not_in:0|exists:account,id',
                'CODE'            => $this->ID > 0 ? 'required|max:20|unique:check,code,' . $this->ID : 'nullable',
                'PAY_TO_ID'       => 'required|not_in:0|exists:contact,id',
                'DATE'            => 'required',
                'LOCATION_ID'     => 'required|exists:location,id',
            ],
            [],
            [
                'BANK_ACCOUNT_ID' => 'Bank Account',
                'CODE'            => 'Reference No.',
                'PAY_TO_ID'       => 'Pay To',
                'DATE'            => 'Date',
                'LOCATION_ID'     => 'Location',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        try {
            if ($this->ID == 0) {

                DB::beginTransaction();
                $this->ID = $this->writeCheckServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->BANK_ACCOUNT_ID,
                    $this->PAY_TO_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    0,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID

                );
                DB::commit();
                return Redirect::route('bankingmake_cheque_edit', ['id' => $this->ID]);
            } else {

                DB::beginTransaction();
                $data = $this->writeCheckServices->Get($this->ID);
                if ($data) {
                    if ($this->STATUS == 16) {
                        $JNO = $this->accountJournalServices->getRecord($this->writeCheckServices->object_type_check, $this->ID);
                        if ($JNO > 0) {
                            // BANK_ACCOUNT_ID on CREDIT
                            $this->accountJournalServices->AccountSwitch(
                                $this->BANK_ACCOUNT_ID,
                                $data->BANK_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->PAY_TO_ID,
                                $this->ID,
                                $this->writeCheckServices->object_type_check,
                                $this->DATE,
                                1
                            );
                            // BANK_ACCOUNT_ID on DEBIT
                            $this->accountJournalServices->AccountSwitch(
                                $this->BANK_ACCOUNT_ID,
                                $data->BANK_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->PAY_TO_ID,
                                $this->ID,
                                $this->writeCheckServices->object_type_check,
                                $this->DATE,
                                0
                            );
                        }
                    }

                    $this->writeCheckServices->Update(
                        $this->ID,
                        $this->CODE,
                        $this->BANK_ACCOUNT_ID,
                        $this->PAY_TO_ID,
                        $this->LOCATION_ID,
                        $this->AMOUNT,
                        $this->NOTES,
                        $this->INPUT_TAX_ID,
                        $this->INPUT_TAX_RATE,
                        $this->INPUT_TAX_AMOUNT,
                        $this->INPUT_TAX_VAT_METHOD,
                        $this->INPUT_TAX_ACCOUNT_ID
                    );

                    DB::commit();
                    $this->writeCheckServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                    $getResult = $this->writeCheckServices->ReComputed($this->ID);
                    $this->getUpdateAmount($getResult);
                    session()->flash('message', 'Successfully updated');
                }
            }
            $this->Modify = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT           = (float) $list['AMOUNT'];
            $this->TOTAL_ITEMS      = (float) $list['ITEM_AMOUNT'];
            $this->TOTAL_EXPENSES   = (float) $list['EXPENSES_AMOUNT'];
            $this->INPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function updatedInputTaxId()
    {
        $this->getTax();
    }
    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->writeCheckServices->document_type_id;
            $data            = $this->writeCheckServices->ItemInventory($this->ID);
            if ($data) {
                $this->itemInventoryServices->InventoryExecute($data, $this->LOCATION_ID, $SOURCE_REF_TYPE, $this->DATE, true);
            }
            return true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function OpenJournal()
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->writeCheckServices->object_type_check, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    private function AccountJournal(): bool
    {
        try {

            $check         = (int) $this->writeCheckServices->object_type_check;
            $checkItems    = (int) $this->writeCheckServices->object_type_check_items;
            $checkExpenses = (int) $this->writeCheckServices->object_type_check_expenses;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($check, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($check, $this->ID) + 1;
            }

            //Item
            $checkItemData = $this->writeCheckServices->getCheckItemJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $checkItemData, $this->LOCATION_ID, $checkItems, $this->DATE, "ASSET");
            //Expenses
            $checkExpensesData = $this->writeCheckServices->getCheckExpenseJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $checkExpensesData, $this->LOCATION_ID, $checkExpenses, $this->DATE, "EXPENSE");

            //Main
            $checkData = $this->writeCheckServices->getCheckJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $checkData, $this->LOCATION_ID, $check, $this->DATE, "AP");

            //Tax
            $checkDataTax = $this->writeCheckServices->getCheckTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $checkDataTax, $this->LOCATION_ID, $check, $this->DATE, "TAX");

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

            $count_item    = (int) $this->writeCheckServices->CountItems($this->ID, true);
            $count_expense = (int) $this->writeCheckServices->CountItems($this->ID, false);
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

            $this->writeCheckServices->StatusUpdate($this->ID, 15);
            DB::commit();
            $data = $this->writeCheckServices->get($this->ID);
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
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->writeCheckServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('bankingmake_cheque_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->writeCheckServices->object_type_check, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function render()
    {
        return view('livewire.write-check.write-check-form');
    }
}
