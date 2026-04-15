<?php
namespace App\Livewire\SalesReceipt;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DepositServices;
use App\Services\DocumentStatusServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentMethodServices;
use App\Services\SalesReceiptServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sales Receipt')]
class SalesReceiptForm extends Component
{

    public bool $BANK_MODE = false;
    public bool $IS_MODAL  = false;
    public int $PATIENT_PAYMENT_ID;
    public int $ID;
    public bool $UNPOSTED = true;
    public int $CUSTOMER_ID;
    public int $SALES_REP_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $CLASS_ID;
    public string $CARD_NO;
    public int $PAYMENT_METHOD_ID;
    public string $PAYMENT_REF_NO;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $OUTPUT_TAX_ID;
    public float $OUTPUT_TAX_RATE;
    public int $OUTPUT_TAX_VAT_METHOD;
    public int $OUTPUT_TAX_ACCOUNT_ID;
    public float $OUTPUT_TAX_AMOUNT;
    public float $AMOUNT;

    public float $TAXABLE_AMOUNT;
    public float $NONTAXABLE_AMOUNT;
    public int $UNDEPOSITED_FUNDS_ACCOUNT_ID = 5;
    public $contactList                      = [];
    public $locationList                     = [];
    public $paymentMethodList                = [];
    public $taxList                          = [];
    public $accountList                      = [];
    public bool $Modify;
    private $locationServices;
    private $contactServices;

    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $salesReceiptServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    private $patientPaymentServices;
    private $paymentMethodServices;
    public bool $showCardNo         = false;
    public bool $showCardDateExpire = false;
    public bool $showReceiptNo      = false;
    public bool $showReceiptDate    = false;
    public bool $showFileName       = false;
    public string $TITLE_REF;
    public string $TITLE_DATE;
    public int $DEPOST_ID = 0;
    private $depositServices;
    public function boot(
        SalesReceiptServices $salesReceiptServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        ItemInventoryServices $itemInventoryServices,
        AccountJournalServices $accountJournalServices,
        PatientPaymentServices $patientPaymentServices,
        PaymentMethodServices $paymentMethodServices,
        DepositServices $depositServices
    ) {
        $this->salesReceiptServices   = $salesReceiptServices;
        $this->locationServices       = $locationServices;
        $this->contactServices        = $contactServices;
        $this->paymentMethodServices  = $paymentMethodServices;
        $this->taxServices            = $taxServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->accountServices        = $accountServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->patientPaymentServices = $patientPaymentServices;
        $this->depositServices        = $depositServices;
    }
    public function LoadDropdown()
    {
        $this->contactList       = $this->contactServices->getCustoPatientList();
        $this->locationList      = $this->locationServices->getList();
        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();
        $this->taxList           = $this->taxServices->getList();
        if ($this->BANK_MODE) {
            $this->accountList = $this->accountServices->getBankAccountDeposit();
            return;
        }

        $this->accountList = $this->accountServices->getUndepositedList();
    }

    public string $tab = "item";
    public function SelectTab(string $select)
    {
        $this->tab = $select;
    }

    public function getTax()
    {
        $tax = $this->taxServices->get($this->OUTPUT_TAX_ID);
        if ($tax) {
            $this->OUTPUT_TAX_RATE       = (float) $tax->OUTPUT_TAX_RATE;
            $this->OUTPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->OUTPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }
    public function updatedpaymentmethodid()
    {
        $paymentMethod = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);

        if ($paymentMethod) {
            $data                     = $this->paymentMethodServices->PaymentMethodSwitch($paymentMethod->PAYMENT_TYPE);
            $this->showCardNo         = (bool) $data['showCardNo'];
            $this->showCardDateExpire = (bool) $data['showCardDateExpire'];
            $this->showReceiptNo      = (bool) $data['showReceiptNo'];
            $this->showReceiptDate    = (bool) $data['showReceiptDate'];
            $this->showFileName       = (bool) $data['showFileName'];
            $this->TITLE_REF          = (string) $data['titleRef'];
            $this->TITLE_DATE         = (string) $data['titleDate'];
            // $this->showTax = (bool) $data['showTax'];
            return;
        }

        $this->showCardNo         = false;
        $this->showCardDateExpire = false;
        $this->showReceiptNo      = false;
        $this->showReceiptDate    = false;
        $this->showFileName       = false;
    }
    private function getInfo($Data)
    {
        $this->ID                           = $Data->ID;
        $this->CODE                         = $Data->CODE;
        $this->DATE                         = $Data->DATE;
        $this->LOCATION_ID                  = $Data->LOCATION_ID;
        $this->CUSTOMER_ID                  = $Data->CUSTOMER_ID;
        $this->SALES_REP_ID                 = $Data->SALES_REP_ID ?? 0;
        $this->CARD_NO                      = $Data->CARD_NO ?? '';
        $this->PAYMENT_METHOD_ID            = $Data->PAYMENT_METHOD_ID ? $Data->PAYMENT_METHOD_ID : 0;
        $this->CLASS_ID                     = $Data->CLASS_ID ? $Data->CLASS_ID : 0;
        $this->PAYMENT_REF_NO               = $Data->PAYMENT_REF_NO ?? '';
        $this->NOTES                        = $Data->NOTES ?? '';
        $this->AMOUNT                       = $Data->AMOUNT;
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $Data->UNDEPOSITED_FUNDS_ACCOUNT_ID;
        $this->STATUS                       = $Data->STATUS;
        if ($this->STATUS == 16) {
            $this->removeJournal();
        }
        $this->OUTPUT_TAX_ID         = $Data->OUTPUT_TAX_ID ? $Data->OUTPUT_TAX_ID : 0;
        $this->OUTPUT_TAX_RATE       = $Data->OUTPUT_TAX_RATE ? $Data->OUTPUT_TAX_RATE : 0;
        $this->OUTPUT_TAX_AMOUNT     = $Data->OUTPUT_TAX_AMOUNT ? $Data->OUTPUT_TAX_AMOUNT : 0;
        $this->OUTPUT_TAX_VAT_METHOD = $Data->OUTPUT_TAX_VAT_METHOD ? $Data->OUTPUT_TAX_VAT_METHOD : 0;
        $this->OUTPUT_TAX_ACCOUNT_ID = $Data->OUTPUT_TAX_ACCOUNT_ID ? $Data->OUTPUT_TAX_ACCOUNT_ID : 0;
        $this->TAXABLE_AMOUNT        = $Data->TAXABLE_AMOUNT ? $Data->TAXABLE_AMOUNT : 0;
        $this->NONTAXABLE_AMOUNT     = $Data->NONTAXABLE_AMOUNT ? $Data->NONTAXABLE_AMOUNT : 0;
        $this->STATUS_DESCRIPTION    = $this->documentStatusServices->getDesc($this->STATUS);
        $this->updatedpaymentmethodid();
        $this->DEPOST_ID = $this->depositServices->getSalesReceipt($this->ID);
    }
    public function mount($id = null, $IS_MODAL = false, $PATIENT_PAYMENT_ID = 0)
    {
        $this->IS_MODAL           = $IS_MODAL;
        $this->PATIENT_PAYMENT_ID = $PATIENT_PAYMENT_ID;

        if (is_numeric($id)) {
            $data = $this->salesReceiptServices->get($id);
            if ($data) {
                $this->LoadDropdown();

                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('customerssales_receipt')->with('error', $errorMessage);
        }

        if ($this->PATIENT_PAYMENT_ID > 0) {
            $pay = $this->patientPaymentServices->get($this->PATIENT_PAYMENT_ID);
            if ($pay) {
                $this->CUSTOMER_ID    = $pay->PATIENT_ID;
                $this->DATE           = $pay->DATE;
                $this->LOCATION_ID    = $pay->LOCATION_ID;
                $this->PAYMENT_REF_NO = $pay->RECEIPT_REF_NO ?? '';
                $this->NOTES          = $pay->NOTES ?? '';
            }
        } else {
            $this->CUSTOMER_ID    = 0;
            $this->DATE           = $this->userServices->getTransactionDateDefault();
            $this->LOCATION_ID    = $this->userServices->getLocationDefault();
            $this->PAYMENT_REF_NO = '';
            $this->NOTES          = '';
        }

        $this->DefaultForm();
    }
    public function DefaultForm()
    {
        $this->LoadDropdown();
        $this->Modify                       = true;
        $this->ID                           = 0;
        $this->CODE                         = '';
        $this->SALES_REP_ID                 = 0;
        $this->CARD_NO                      = "";
        $this->CLASS_ID                     = 0;
        $this->PAYMENT_METHOD_ID            = 1;
        $this->AMOUNT                       = 0;
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $this->BANK_MODE ? 0 : $this->accountServices->UNDEPOSITED_ACCOUNT_ID;
        $this->STATUS                       = 0;
        $this->OUTPUT_TAX_ID                = (int) $this->systemSettingServices->GetValue('OutputTaxId');
        $this->OUTPUT_TAX_RATE              = 0;
        $this->OUTPUT_TAX_AMOUNT            = 0;
        $this->OUTPUT_TAX_VAT_METHOD        = 0;
        $this->OUTPUT_TAX_ACCOUNT_ID        = 0;
        $this->TAXABLE_AMOUNT               = 0;
        $this->NONTAXABLE_AMOUNT            = 0;
        $this->STATUS_DESCRIPTION           = "";
        $this->updatedpaymentmethodid();
        $this->getTax();
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    private function getPatientItemAutoSave()
    {
        if ($this->PATIENT_PAYMENT_ID > 0) {
            $dataList = $this->patientPaymentServices->PaymentChargesList($this->PATIENT_PAYMENT_ID, 0);
            foreach ($dataList as $list) {
                $this->salesReceiptServices->ItemStore(
                    $this->ID,
                    $list->ITEM_ID,
                    $list->QUANTITY,
                    $list->UNIT_ID ?? 0,
                    $list->UNIT_BASE_QUANTITY ?? 1,
                    $list->AMOUNT_APPLIED / $list->QUANTITY,
                    $list->RATE_TYPE,
                    $list->AMOUNT_APPLIED,
                    $list->TAXABLE,
                    $list->TAXABLE_AMOUNT,
                    $list->TAX_AMOUNT,
                    0,
                    0,
                    $this->patientPaymentServices->SALES_ON_CASH,
                    0,
                    0,
                    0,
                    0,
                    0
                );
            }
        }
    }
    public function save()
    {
        try {
            if ($this->ID == 0) {

                $this->validate(
                    [
                        'CUSTOMER_ID'                  => 'required|not_in:0|exists:contact,id',
                        'OUTPUT_TAX_ID'                => 'required|not_in:0',
                        'DATE'                         => 'required|date|date_format:Y-m-d',
                        'LOCATION_ID'                  => 'required|not_in:0|exists:location,id',
                        'PAYMENT_METHOD_ID'            => 'required|exists:payment_method,id',
                        'UNDEPOSITED_FUNDS_ACCOUNT_ID' => 'required|exists:account,id',
                        'OUTPUT_TAX_ACCOUNT_ID'        => 'required|exists:account,id',
                    ],
                    [],
                    [
                        'CUSTOMER_ID'                  => 'Customer',
                        'OUTPUT_TAX_ID'                => 'Tax',
                        'DATE'                         => 'Date',
                        'LOCATION_ID'                  => 'Location',
                        'PAYMENT_METHOD_ID'            => 'Payment Method',
                        'UNDEPOSITED_FUNDS_ACCOUNT_ID' => 'Bank Accounts',
                        'OUTPUT_TAX_ACCOUNT_ID'        => 'Output Tax Accounts',
                    ]
                );

                if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                    session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                    return;
                }

                DB::beginTransaction();
                $this->getTax();
                $this->ID = (int) $this->salesReceiptServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->AMOUNT,
                    $this->AMOUNT,
                    $this->PAYMENT_METHOD_ID,
                    $this->PAYMENT_REF_NO,
                    $this->CARD_NO,
                    0,
                    0,
                    $this->NOTES,
                    $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_AMOUNT,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID,
                    $this->STATUS
                );

                if ($this->PATIENT_PAYMENT_ID > 0) {
                    $this->getPatientItemAutoSave();
                    $this->patientPaymentServices->CustomerRef($this->PATIENT_PAYMENT_ID, false, $this->ID);
                    $this->salesReceiptServices->getUpdateTaxItem($this->ID, $this->OUTPUT_TAX_ID);
                    $getResult = $this->salesReceiptServices->ReComputed($this->ID);
                    $result    = (bool) $this->Posting();
                    if ($result == false) {
                        DB::rollBack();
                        return;
                    }
                }
                DB::commit();
                if ($this->IS_MODAL) {
                    return Redirect::route('customerssales_receipt_edit', ['id' => $this->ID])->with('message', 'Successfully created');
                }
            } else {

                $this->validate(
                    [
                        'CUSTOMER_ID'                  => 'required|not_in:0',
                        'CODE'                         => 'required|max:20|unique:sales_receipt,code,' . $this->ID,
                        'OUTPUT_TAX_ID'                => 'required|not_in:0',
                        'DATE'                         => 'required',
                        'LOCATION_ID'                  => 'required',
                        'PAYMENT_METHOD_ID'            => 'required|exists:payment_method,id',
                        'UNDEPOSITED_FUNDS_ACCOUNT_ID' => 'required|exists:account,id',
                        'OUTPUT_TAX_ACCOUNT_ID'        => 'required|exists:account,id',
                    ],
                    [],
                    [
                        'CUSTOMER_ID'                  => 'Customer',
                        'CODE'                         => 'Reference No.',
                        'OUTPUT_TAX_ID'                => 'Tax',
                        'DATE'                         => 'Date',
                        'LOCATION_ID'                  => 'Location',
                        'PAYMENT_TERMS_ID'             => 'Payment Terms',
                        'UNDEPOSITED_FUNDS_ACCOUNT_ID' => 'Bank Accounts',
                        'OUTPUT_TAX_ACCOUNT_ID'        => 'Output Tax Accounts',
                    ]
                );

                DB::beginTransaction();
                $data = $this->salesReceiptServices->Get($this->ID);
                if ($data) {
                    if ($this->STATUS == 16) {
                        $JNO = $this->accountJournalServices->getRecord($this->salesReceiptServices->object_type_sales_receipt, $this->ID);
                        if ($JNO > 0) {
                            // ACCOUNTS_RECEIVABLE_ID
                            $this->accountJournalServices->AccountSwitch($this->UNDEPOSITED_FUNDS_ACCOUNT_ID, $data->UNDEPOSITED_FUNDS_ACCOUNT_ID, $this->LOCATION_ID, $JNO, $data->CUSTOMER_ID, $this->ID, $this->salesReceiptServices->object_type_sales_receipt, $this->DATE, 0);
                            // OUTPUT_TAX_ACCOUNT_ID
                            $this->accountJournalServices->AccountSwitch($this->OUTPUT_TAX_ACCOUNT_ID, $data->OUTPUT_TAX_ACCOUNT_ID, $this->LOCATION_ID, $JNO, $data->CUSTOMER_ID, $this->ID, $this->salesReceiptServices->object_type_sales_receipt, $this->DATE, 1);
                        }
                    }
                }

                $this->getTax();
                $this->salesReceiptServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->AMOUNT,
                    $this->AMOUNT,
                    $this->PAYMENT_METHOD_ID,
                    $this->PAYMENT_REF_NO,
                    $this->CARD_NO,
                    0,
                    0,
                    $this->NOTES,
                    $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_AMOUNT,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID,
                    $this->STATUS,

                );

                $this->salesReceiptServices->getUpdateTaxItem($this->ID, $this->OUTPUT_TAX_ID);
                $getResult = $this->salesReceiptServices->ReComputed($this->ID);
                DB::commit();
                $this->getUpdateAmount($getResult);
                session()->flash('message', 'Successfully updated');
            }

            $data = $this->salesReceiptServices->get($this->ID);

            if ($data) {
                $this->getInfo($data);
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
            $this->AMOUNT            = $list['AMOUNT'];
            $this->OUTPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
            $this->TAXABLE_AMOUNT    = $list['TAXABLE_AMOUNT'];
            $this->NONTAXABLE_AMOUNT = $list['NONTAXABLE_AMOUNT'];
        }
    }
    #[On('update-status')]
    public function updateStatus()
    {
        $data = $this->salesReceiptServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function updateCancel()
    {
        $data = $this->salesReceiptServices->get($this->ID);
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
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->salesReceiptServices->object_type_sales_receipt, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->salesReceiptServices->document_type_id;
            $data            = $this->salesReceiptServices->ItemInventory($this->ID);
            if ($data) {
                $this->itemInventoryServices->InventoryExecute(
                    $data,
                    $this->LOCATION_ID,
                    $SOURCE_REF_TYPE,
                    $this->DATE,
                    false
                );
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

            $salesReceiptId      = (int) $this->salesReceiptServices->object_type_sales_receipt;
            $salesReceiptItemsId = (int) $this->salesReceiptServices->object_type_sales_receipt_items;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($salesReceiptId, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($salesReceiptId, $this->ID) + 1;
            }

            //Main
            $salesreceiptData = $this->salesReceiptServices->getJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $salesreceiptData, $this->LOCATION_ID, $salesReceiptId, $this->DATE);
            //Tax
            $salesReceiptDataTax = $this->salesReceiptServices->getTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $salesReceiptDataTax, $this->LOCATION_ID, $salesReceiptId, $this->DATE);

            //Income
            $salesReceiptItemData = $this->salesReceiptServices->getInvoiceItemJournalIncome($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $salesReceiptItemData, $this->LOCATION_ID, $salesReceiptItemsId, $this->DATE);

            //cogs
            $salesReceiptItemCogs = $this->salesReceiptServices->getInvoiceItemJournalCogs($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $salesReceiptItemCogs, $this->LOCATION_ID, $salesReceiptItemsId, $this->DATE);

            //Asset
            $salesReceiptItemAsset = $this->salesReceiptServices->getInvoiceItemJournalAsset($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $salesReceiptItemAsset, $this->LOCATION_ID, $salesReceiptItemsId, $this->DATE);

            //Checking if balance
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

            $count = (int) $this->salesReceiptServices->CountItems($this->ID);
            if ($count == 0) {
                session()->flash('error', 'Item not found.');
                return;
            }

            DB::beginTransaction();
            $result = (bool) $this->Posting();
            if ($result) {
                DB::rollBack();
                return;
            }
            DB::commit();
            Redirect::route('customerssales_receipt_edit', $this->ID)->with('message', 'Successfully posted');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function Posting(): bool
    {

        if ($this->contactServices->IsNotPatient($this->CUSTOMER_ID)) {
            if (! $this->ItemInventory()) {
                return false;
            }
        }

        if (! $this->AccountJournal()) {
            return false;
        }
        $this->salesReceiptServices->StatusUpdate($this->ID, 15);
        return true;
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->salesReceiptServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('customerssales_receipt_edit', $this->ID)->with('message', 'Successfully posted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->salesReceiptServices->object_type_sales_receipt, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function delete()
    {
        try {
            DB::beginTransaction();
            $data = $this->salesReceiptServices->get($this->ID);
            if ($data) {
                if ($data->STATUS == 16) {
                    $JOURNAL_NO = $this->accountJournalServices->getRecord(
                        $this->salesReceiptServices->object_type_sales_receipt,
                        $this->ID
                    );
                    //Main
                    $this->accountJournalServices->DeleteJournal(
                        $data->UNDEPOSITED_FUNDS_ACCOUNT_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $this->ID,
                        $this->salesReceiptServices->object_type_sales_receipt,
                        $data->DATE,
                        0,

                    );

                    //Tax
                    $this->accountJournalServices->DeleteJournal(
                        $data->OUTPUT_TAX_ACCOUNT_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $this->ID,
                        $this->salesReceiptServices->object_type_sales_receipt,
                        $data->DATE,
                        1,

                    );

                    $dataItem = $this->salesReceiptServices->ItemView($this->ID);
                    foreach ($dataItem as $list) {
                        $this->deleteItem($list->ID, $this->ID, $JOURNAL_NO);
                    }
                    $PP_ID = $this->patientPaymentServices->GetCustomerRef(false, $this->ID);
                    if ($PP_ID > 0) {
                        $this->patientPaymentServices->CustomerRef($PP_ID, false, 0);
                    }
                    $this->salesReceiptServices->Delete($this->ID);
                    DB::commit();
                }
            }

            return Redirect::route('customerssales_receipt')->with('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function deleteItem(int $Id, int $SALES_RECEIPT_ID, int $JOURNAL_NO)
    {

        $sr = $this->salesReceiptServices->get($SALES_RECEIPT_ID);
        if ($sr) {
            $srItem = $this->salesReceiptServices->ItemGet($Id, $SALES_RECEIPT_ID, );
            if ($srItem) {
                // Inventory
                $this->itemInventoryServices->InventoryModify(
                    $srItem->ITEM_ID,
                    $sr->LOCATION_ID,
                    $Id,
                    $this->salesReceiptServices->document_type_id,
                    $sr->DATE,
                    0,
                    0,
                    0
                );

                // INCOME_ACCOUNT_ID

                if ($srItem->INCOME_ACCOUNT_ID) {
                    $this->accountJournalServices->DeleteJournal(
                        $srItem->INCOME_ACCOUNT_ID,
                        $sr->LOCATION_ID,
                        $JOURNAL_NO,
                        $srItem->ITEM_ID,
                        $Id,
                        $this->salesReceiptServices->object_type_sales_receipt_items,
                        $sr->DATE,
                        1,

                    );
                }

                // COGS_ACCOUNT_ID
                if ($srItem->COGS_ACCOUNT_ID) {
                    $this->accountJournalServices->DeleteJournal(
                        $srItem->COGS_ACCOUNT_ID,
                        $sr->LOCATION_ID,
                        $JOURNAL_NO,
                        $srItem->ITEM_ID,
                        $Id,
                        $this->salesReceiptServices->object_type_sales_receipt_items,
                        $sr->DATE,
                        0,
                    );
                }

                // ASSET_ACCOUNT_ID
                if ($srItem->ASSET_ACCOUNT_ID) {
                    $this->accountJournalServices->DeleteJournal(
                        $srItem->ASSET_ACCOUNT_ID,
                        $sr->LOCATION_ID,
                        $JOURNAL_NO,
                        $srItem->ITEM_ID,
                        $Id,
                        $this->salesReceiptServices->object_type_sales_receipt_items,
                        $sr->DATE,
                        1,

                    );
                }
            }
        }
    }
    public function render()
    {
        return view('livewire.sales-receipt.sales-receipt-form');
    }
}
