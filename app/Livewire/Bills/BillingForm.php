<?php
namespace App\Livewire\Bills;

use App\Enums\DocStatus;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\DocumentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\PaymentTermServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Billing')]
class BillingForm extends Component
{

    public int $PHILHEALTH_ID = 0;
    use WithFileUploads;
    public $PDF               = null;
    public bool $showFileName = true;
    public string $FILE_NAME;
    public string $FILE_PATH;
    public bool $IS_CONFIRM;
    public string $DATE_CONFIRM = '';
    public int $openStatus      = 0;
    public int $ID;
    public int $VENDOR_ID;
    public bool $UNPOSTED = true;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ACCOUNTS_PAYABLE_ID;
    public string $DUE_DATE;
    public string $DISCOUNT_DATE;
    public float $DISCOUNT_PCT;
    public int $PAYMENT_TERMS_ID;
    public string $NOTES;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;
    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $BALANCE_DUE;
    public float $PAYMENT;
    public bool $useAccount = true;
    public $vendorList      = [];
    public $locationList    = [];
    public $paymentTermList = [];
    public $taxList         = [];
    public bool $Modify;
    private $billingServices;
    public $accountList = [];
    private $locationServices;
    private $contactServices;
    private $paymentTermServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $objectServices;
    private $accountJournalServices;
    private $accountServices;
    private $documentTypeServices;
    private $itemInventoryServices;
    private $uploadServices;
    private $dateServices;
    private $philHealthProfFeeServices;

    public function boot(
        ItemInventoryServices $itemInventoryServices,
        DocumentTypeServices $documentTypeServices,
        BillingServices $billingServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        PaymentTermServices $paymentTermServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        ObjectServices $objectServices,
        AccountJournalServices $accountJournalServices,
        AccountServices $accountServices,
        UploadServices $uploadServices,
        DateServices $dateServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices,

    ) {
        $this->billingServices           = $billingServices;
        $this->locationServices          = $locationServices;
        $this->contactServices           = $contactServices;
        $this->paymentTermServices       = $paymentTermServices;
        $this->taxServices               = $taxServices;
        $this->userServices              = $userServices;
        $this->documentStatusServices    = $documentStatusServices;
        $this->systemSettingServices     = $systemSettingServices;
        $this->objectServices            = $objectServices;
        $this->accountJournalServices    = $accountJournalServices;
        $this->accountServices           = $accountServices;
        $this->documentTypeServices      = $documentTypeServices;
        $this->itemInventoryServices     = $itemInventoryServices;
        $this->uploadServices            = $uploadServices;
        $this->dateServices              = $dateServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;

    }
    private function getInfoPF()
    {
        $result = $this->philHealthProfFeeServices->GetBill($this->ID);
        if ($result) {
            $this->PHILHEALTH_ID = $result->PHIC_ID ?? 0;
        }
    }
    public function updatedCUSTOMFIELD1()
    {
        $this->validate([
            'CUSTOM_FIELD1' => 'file|mimes:pdf|max:10240', // PDF file, max 10MB
        ], [], [
            'CUSTOM_FIELD1' => 'Files',
        ]);
    }
    public function updatedInputTaxId()
    {
        $this->getTax();
    }
    public function getConfirm()
    {
        $this->billingServices->ConfirmProccess($this->ID);
        return Redirect::route('patientspayment_edit', ['id' => $this->ID])->with('message', 'Successfully confirm');
    }
    public function getDocumentProccess()
    {
        $returnData = $this->uploadServices->BillFile($this->PDF);
        $this->billingServices->UpdateFile(
            $this->ID,
            $returnData['filename'] . '.' . $returnData['extension'],
            $returnData['new_path']
        );
        $this->PDF;
    }
    public string $tab = 'item';
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function LoadDropdown()
    {
        $this->accountList     = $this->accountServices->getPayable();
        $this->vendorList      = $this->contactServices->getVendorDoc();
        $this->locationList    = $this->locationServices->getList();
        $this->paymentTermList = $this->paymentTermServices->getList();
        $this->taxList         = $this->taxServices->getList();
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
    public function updatedPAYMENTTERMSID()
    {
        $this->DUE_DATE = $this->paymentTermServices->getDueDate($this->PAYMENT_TERMS_ID, $this->DATE);
    }

    public function updatedDate()
    {
        $this->updatedPAYMENTTERMSID();
    }
    private function getInfo($data)
    {

        $this->ID               = $data->ID;
        $this->CODE             = $data->CODE;
        $this->DATE             = $data->DATE;
        $this->DUE_DATE         = $data->DUE_DATE ? $data->DUE_DATE : '';
        $this->DISCOUNT_DATE    = $data->DISCOUNT_DATE ? $data->DISCOUNT_DATE : '';
        $this->DISCOUNT_PCT     = $data->DISCOUNT_PCT ? $data->DISCOUNT_PCT : 0;
        $this->LOCATION_ID      = $data->LOCATION_ID;
        $this->VENDOR_ID        = $data->VENDOR_ID;
        $this->PAYMENT_TERMS_ID = $data->PAYMENT_TERMS_ID ? $data->PAYMENT_TERMS_ID : 0;
        $this->NOTES            = $data->NOTES;
        $this->AMOUNT           = $data->AMOUNT ?? 0;
        $this->BALANCE_DUE      = $data->BALANCE_DUE ?? 0;
        $this->PAYMENT          = $this->AMOUNT - $this->BALANCE_DUE;
        $this->STATUS           = $data->STATUS;

        if ($this->STATUS == 16) {
            $this->removeJournal();
        }
        $this->INPUT_TAX_ID         = $data->INPUT_TAX_ID > 0 ? $data->INPUT_TAX_ID : 0;
        $this->INPUT_TAX_RATE       = $data->INPUT_TAX_RATE > 0 ? $data->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT     = $data->INPUT_TAX_AMOUNT > 0 ? $data->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $data->INPUT_TAX_VAT_METHOD > 0 ? $data->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $data->INPUT_TAX_ACCOUNT_ID > 0 ? $data->INPUT_TAX_ACCOUNT_ID : 0;
        $this->STATUS_DESCRIPTION   = $this->documentStatusServices->getDesc($this->STATUS);
        $this->ACCOUNTS_PAYABLE_ID  = $data->ACCOUNTS_PAYABLE_ID;
        $this->FILE_NAME            = $data->FILE_NAME ?? '';
        $this->FILE_PATH            = $data->FILE_PATH ?? '';

        if (empty($this->FILE_NAME)) {
            $this->showFileName = false;
        }
        $this->DATE_CONFIRM = $data->DATE_CONFIRM ?? '';
        if (empty($this->DATE_CONFIRM) == false) {
            $this->IS_CONFIRM = true;
        } else {
            $this->IS_CONFIRM = false;
        }
        if ($this->billingServices->isItemTab($data->ID)) {
            $this->tab = "item";
            return;
        }
        $this->tab = "account";
    }

    public function mount($id = null)
    {
        if (is_numeric($id)) {
            try {
                $Bill = $this->billingServices->get($id);
                if ($Bill) {
                    $this->LoadDropdown();
                    $this->getInfo($Bill);
                    $this->getInfoPF();
                    $this->Modify = false;
                    return;
                }

                $errorMessage = 'Error occurred: Record not found. ';
                return Redirect::route('vendorsbills')->with('error', $errorMessage);
            } catch (\Exception $e) {
                $errorMessage = 'Error occurred: ' . $e->getMessage();
                return Redirect::route('vendorsbills')->with('error', $errorMessage);
            }
        }

        $this->LoadDropdown();
        $this->tab              = "item";
        $this->Modify           = true;
        $this->ID               = 0;
        $this->CODE             = '';
        $this->DATE             = $this->userServices->getTransactionDateDefault();
        $this->DUE_DATE         = '';
        $this->DISCOUNT_DATE    = '';
        $this->DISCOUNT_PCT     = 0;
        $this->LOCATION_ID      = $this->userServices->getLocationDefault();
        $this->VENDOR_ID        = 0; //$this->contactServices->getFirstFromListByID(0);
        $this->PAYMENT_TERMS_ID = (int) $this->systemSettingServices->GetValue('DefaultPaymentTermsId');
        $this->updatedPAYMENTTERMSID();
        $this->NOTES                = '';
        $this->AMOUNT               = 0;
        $this->BALANCE_DUE          = 0;
        $this->PAYMENT              = $this->AMOUNT - $this->BALANCE_DUE;
        $this->STATUS               = 0;
        $this->INPUT_TAX_ID         = (int) $this->systemSettingServices->GetValue('InputTaxId');
        $this->INPUT_TAX_RATE       = 0;
        $this->INPUT_TAX_AMOUNT     = 0;
        $this->INPUT_TAX_VAT_METHOD = 0;
        $this->INPUT_TAX_ACCOUNT_ID = 0;
        $this->STATUS_DESCRIPTION   = "";
        $this->ACCOUNTS_PAYABLE_ID  = $this->accountServices->getByName('Accounts Payable');
        $this->getTax();
        $this->FILE_NAME    = '';
        $this->FILE_PATH    = '';
        $this->DATE_CONFIRM = '';
        $this->IS_CONFIRM   = false;
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function delete()
    {
        try {

            if ($this->ID > 0) {
                DB::beginTransaction();
                if ($this->STATUS == 16) {

                    $JN = $this->accountJournalServices->getJournalNo($this->billingServices->object_type_map_bill, $this->ID);
                    if ($JN > 0) {
                        $this->accountJournalServices->DeleteRecordJournal($JN, $this->DATE, $this->LOCATION_ID);
                    }

                    $itemList = $this->billingServices->ItemView($this->ID);
                    foreach ($itemList as $list) {
                        $this->itemInventoryServices->InventoryModify(
                            $list->ITEM_ID,
                            $this->LOCATION_ID,
                            $list->ID,
                            $this->billingServices->document_type_id,
                            $this->DATE,
                            0,
                            0,
                            0
                        );
                    }
                }
                $result = $this->billingServices->Delete($this->ID);
                if ($result) {
                    DB::commit();
                    return Redirect::route('vendorsbills')->with('message', 'Successfully deleted');
                } else {
                    DB::rollBack();
                }

            }
        } catch (\Throwable $th) {
            //throw $th;
            session()->flash('error', $th->getMessage());
            DB::rollBack();
        }

    }
    public function save()
    {

        $this->validate(
            [
                'VENDOR_ID'            => 'required|integer|exists:contact,id',
                'CODE'                 => 'nullable|max:20|unique:bill,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'INPUT_TAX_ID'         => 'required|not_in:0|exists:tax,id',
                'DATE'                 => 'required|string|date_format:Y-m-d',
                'LOCATION_ID'          => 'required|integer|exists:location,id',
                'PAYMENT_TERMS_ID'     => 'required|integer|exists:payment_terms,id',
                'ACCOUNTS_PAYABLE_ID'  => 'required|integer|exists:account,id',
                'INPUT_TAX_ACCOUNT_ID' => 'required|integer|exists:account,id',
            ],
            [],
            [
                'VENDOR_ID'            => 'Vendor',
                'CODE'                 => 'Reference No.',
                'INPUT_TAX_ID'         => 'Tax',
                'DATE'                 => 'Date',
                'LOCATION_ID'          => 'Location',
                'PAYMENT_TERMS_ID'     => 'Payment Terms',
                'ACCOUNTS_PAYABLE_ID'  => 'Account Payables',
                'INPUT_TAX_ACCOUNT_ID' => 'Account Tax',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        try {
            if ($this->ID == 0) {

                $this->getTax();
                DB::beginTransaction();
                $this->ID = $this->billingServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->DISCOUNT_DATE,
                    $this->DISCOUNT_PCT,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID,
                    $this->STATUS
                );

                if ($this->PDF) {
                    $this->getDocumentProccess();
                }
                DB::commit();
                return Redirect::route('vendorsbills_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                DB::beginTransaction();
                $data = $this->billingServices->Get($this->ID);
                if ($data) {
                    if ($this->STATUS == 16) {
                        // possible having change date
                        $JNO = (int) $this->accountJournalServices->getRecord($this->billingServices->object_type_map_bill, $this->ID);

                        if ($JNO > 0) {

                            $this->accountJournalServices->updateObjectDate($JNO, $this->DATE);
                            // ACCOUNTS_PAYABLE_ID
                            $this->accountJournalServices->AccountSwitch(
                                $this->ACCOUNTS_PAYABLE_ID,
                                $data->ACCOUNTS_PAYABLE_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->VENDOR_ID,
                                $this->ID,
                                $this->billingServices->object_type_map_bill,
                                $this->DATE,
                                1
                            );
                            // INPUT_TAX_ACCOUNT_ID

                            $this->accountJournalServices->AccountSwitch(
                                $this->INPUT_TAX_ACCOUNT_ID,
                                $data->INPUT_TAX_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->VENDOR_ID,
                                $this->ID,
                                $this->billingServices->object_type_map_bill,
                                $this->DATE,
                                0
                            );
                        }
                    }
                }

                $this->getTax();
                $this->billingServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->VENDOR_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID,
                    $this->DATE
                );

                if ($this->PDF) {
                    $this->uploadServices->RemoveIfExists($this->FILE_PATH);
                    $this->getDocumentProccess();
                    $data = $this->billingServices->get($this->ID);
                    if ($data) {
                        $this->getInfo($data);
                    }
                }

                DB::commit();
                $this->billingServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                $getResult = $this->billingServices->ReComputed($this->ID);
                $this->getUpdateAmount($getResult);

                session()->flash('message', 'Successfully updated');
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
            $this->AMOUNT           = $list['AMOUNT'];
            $this->BALANCE_DUE      = $list['BALANCE_DUE'];
            $this->INPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
            $this->PAYMENT          = $this->AMOUNT - $this->BALANCE_DUE;
        }
    }
    public function updateCancel()
    {
        $data = $this->billingServices->get($this->ID);
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

    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->documentTypeServices->getId('Bill');
            $data            = $this->billingServices->ItemInventory($this->ID);
            if ($data) {
                $noProblem = (bool) $this->itemInventoryServices->ChangeDate($data, $this->LOCATION_ID, $SOURCE_REF_TYPE, $this->DATE);

                if ($noProblem == false) {
                    session()->flash('error', 'change date have problem');
                    return false;
                }

                // execute inventory
                $this->itemInventoryServices->InventoryExecute(
                    $data,
                    $this->LOCATION_ID,
                    $SOURCE_REF_TYPE,
                    $this->DATE,
                    true
                );

                if ($this->DATE != $this->dateServices->NowDate()) {
                    // recompute ending onhand
                    // if the date is not today, we need to recompute ending onhand
                    foreach ($data as $list) {
                        $this->itemInventoryServices->RecomputedEndingOnhand(
                            $list->ID,
                            $SOURCE_REF_TYPE,
                            $this->LOCATION_ID,
                        );
                    }
                }

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
        $bills      = (int) $this->objectServices->ObjectTypeID('BILL');
        $JOURNAL_NO = $this->accountJournalServices->getRecord($bills, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    private function AccountJournal(): bool
    {
        try {

            $bills        = (int) $this->billingServices->object_type_map_bill;
            $billItems    = (int) $this->billingServices->object_type_map_bill_item;
            $billExpenses = (int) $this->billingServices->object_type_map_bill_expenses;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billingServices->object_type_map_bill, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->billingServices->object_type_map_bill, $this->ID) + 1;
            }

            //Item
            $billCreditItemData = $this->billingServices->getBillItemJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditItemData, $this->LOCATION_ID, $billItems, $this->DATE, "ASSET");
            //Expenses
            $billCreditExpensesData = $this->billingServices->getBillExpenseJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditExpensesData, $this->LOCATION_ID, $billExpenses, $this->DATE, "EXPENSE");

            //Main
            $billData = $this->billingServices->getBillJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billData, $this->LOCATION_ID, $bills, $this->DATE, "AP");

            //Tax
            $billDataTax = $this->billingServices->getBillTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billDataTax, $this->LOCATION_ID, $bills, $this->DATE, "TAX");

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

            $count_item    = (int) $this->billingServices->CountItems($this->ID, true);
            $count_expense = (int) $this->billingServices->CountItems($this->ID, false);
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

            $this->billingServices->StatusUpdate($this->ID, (int)DocStatus::POSTED->value);
            DB::commit();
            $data = $this->billingServices->get($this->ID);
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
            $this->billingServices->StatusUpdate($this->ID, (int)DocStatus::UNPOSTED->value);
            $this->removeJournal();
            DB::commit();
            Redirect::route('vendorsbills_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billingServices->object_type_map_bill, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function render()
    {
        return view('livewire.bills.billing-form');
    }
}
