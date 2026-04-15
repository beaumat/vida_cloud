<?php
namespace App\Livewire\Invoice;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\InvoiceServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentTermServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use App\Services\PriceLevelLineServices;
use App\Services\ServiceChargeServices;
use App\Services\ShipViaServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Invoice')]
class InvoiceForm extends Component
{
    public bool $IS_MODAL = false;
    public int $PATIENT_PAYMENT_ID;
    public int $ID;
    public bool $UNPOSTED = true;
    public int $CUSTOMER_ID;
    public int $SALES_REP_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $CLASS_ID;
    public int $SHIP_VIA_ID;
    public $DUE_DATE;
    public $SHIP_DATE;
    public int $PAYMENT_TERMS_ID;
    public string $PO_NUMBER;
    public $DISCOUNT_DATE;
    public float $DISCOUNT_PCT;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $OUTPUT_TAX_ID;
    public float $OUTPUT_TAX_RATE;
    public int $OUTPUT_TAX_VAT_METHOD;
    public int $OUTPUT_TAX_ACCOUNT_ID;
    public float $OUTPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $BALANCE_DUE;
    public float $TAXABLE_AMOUNT;
    public float $NONTAXABLE_AMOUNT;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public $contactList     = [];
    public $locationList    = [];
    public $shipViaList     = [];
    public $paymentTermList = [];
    public $taxList         = [];
    public $accountList     = [];
    public bool $Modify;
    public int $BILL_ID;
    private $locationServices;
    private $contactServices;
    private $shipViaServices;
    private $paymentTermServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $invoiceServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    private $patientPaymentServices;
    private $philHealthServices;
    private $priceLevelLineServices;
    private $serviceChargeServices;
    private $philHealthProfFeeServices;
    public string $tab = "item";
    public function SelectTab(string $select)
    {
        $this->tab = $select;
    }
    public function boot(
        InvoiceServices $invoiceServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        ShipViaServices $shipViaServices,
        PaymentTermServices $paymentTermServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        ItemInventoryServices $itemInventoryServices,
        AccountJournalServices $accountJournalServices,
        PatientPaymentServices $patientPaymentServices,
        PhilHealthServices $philHealthServices,
        PriceLevelLineServices $priceLevelLineServices,
        ServiceChargeServices $serviceChargeServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices
    ) {
        $this->invoiceServices           = $invoiceServices;
        $this->locationServices          = $locationServices;
        $this->contactServices           = $contactServices;
        $this->shipViaServices           = $shipViaServices;
        $this->paymentTermServices       = $paymentTermServices;
        $this->taxServices               = $taxServices;
        $this->userServices              = $userServices;
        $this->documentStatusServices    = $documentStatusServices;
        $this->systemSettingServices     = $systemSettingServices;
        $this->accountServices           = $accountServices;
        $this->itemInventoryServices     = $itemInventoryServices;
        $this->accountJournalServices    = $accountJournalServices;
        $this->patientPaymentServices    = $patientPaymentServices;
        $this->philHealthServices        = $philHealthServices;
        $this->priceLevelLineServices    = $priceLevelLineServices;
        $this->serviceChargeServices     = $serviceChargeServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;

    }
    public function LoadDropdown()
    {
        $this->contactList     = $this->contactServices->getCustoPatientList();
        $this->locationList    = $this->locationServices->getList();
        $this->shipViaList     = $this->shipViaServices->getList();
        $this->paymentTermList = $this->paymentTermServices->getList();
        $this->taxList         = $this->taxServices->getList();
        $this->accountList     = $this->accountServices->getReceivable();
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

    private function getInfo($Data)
    {
        $this->ID                     = $Data->ID;
        $this->CODE                   = $Data->CODE;
        $this->DATE                   = $Data->DATE;
        $this->DUE_DATE               = $Data->DUE_DATE ?? null;
        $this->LOCATION_ID            = $Data->LOCATION_ID;
        $this->CUSTOMER_ID            = $Data->CUSTOMER_ID;
        $this->SALES_REP_ID           = $Data->SALES_REP_ID ?? 0;
        $this->SHIP_VIA_ID            = $Data->SHIP_VIA_ID ?? 0;
        $this->PAYMENT_TERMS_ID       = $Data->PAYMENT_TERMS_ID ? $Data->PAYMENT_TERMS_ID : 0;
        $this->CLASS_ID               = $Data->CLASS_ID ? $Data->CLASS_ID : 0;
        $this->PO_NUMBER              = $Data->PO_NUMBER ?? '';
        $this->DISCOUNT_DATE          = $Data->DISCOUNT_DATE ?? null;
        $this->DISCOUNT_PCT           = $Data->DISCOUNT_PCT ?? 0;
        $this->NOTES                  = $Data->NOTES ?? '';
        $this->AMOUNT                 = $Data->AMOUNT;
        $this->BALANCE_DUE            = $Data->BALANCE_DUE;
        $this->ACCOUNTS_RECEIVABLE_ID = $Data->ACCOUNTS_RECEIVABLE_ID;
        $this->STATUS                 = $Data->STATUS;
        $this->OUTPUT_TAX_ID          = $Data->OUTPUT_TAX_ID ? $Data->OUTPUT_TAX_ID : 0;
        $this->OUTPUT_TAX_RATE        = $Data->OUTPUT_TAX_RATE ? $Data->OUTPUT_TAX_RATE : 0;
        $this->OUTPUT_TAX_AMOUNT      = $Data->OUTPUT_TAX_AMOUNT ? $Data->OUTPUT_TAX_AMOUNT : 0;
        $this->OUTPUT_TAX_VAT_METHOD  = $Data->OUTPUT_TAX_VAT_METHOD ? $Data->OUTPUT_TAX_VAT_METHOD : 0;
        $this->OUTPUT_TAX_ACCOUNT_ID  = $Data->OUTPUT_TAX_ACCOUNT_ID ? $Data->OUTPUT_TAX_ACCOUNT_ID : 0;
        $this->TAXABLE_AMOUNT         = $Data->TAXABLE_AMOUNT ? $Data->TAXABLE_AMOUNT : 0;
        $this->NONTAXABLE_AMOUNT      = $Data->NONTAXABLE_AMOUNT ? $Data->NONTAXABLE_AMOUNT : 0;
        $this->STATUS_DESCRIPTION     = $this->documentStatusServices->getDesc($this->STATUS);
        if ($this->STATUS == 16) {
            $this->removeJournal();
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
    public function mount($id = null, $IS_MODAL = false, $PATIENT_PAYMENT_ID = 0)
    {
        $this->IS_MODAL           = $IS_MODAL;
        $this->PATIENT_PAYMENT_ID = $PATIENT_PAYMENT_ID;

        if (is_numeric($id)) {
            $data = $this->invoiceServices->get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;

                $PhicData = $this->philHealthServices->getDataByInvoiceId($this->ID);
                if ($PhicData) {
                    $PF_DATA = $this->philHealthProfFeeServices->getProfFeeFirst($PhicData->ID);
                    if ($PF_DATA) {
                        $this->BILL_ID = $PF_DATA->BILL_ID ?? 0;
                    }

                }

                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('customersinvoice')->with('error', $errorMessage);
        }

        if ($this->PATIENT_PAYMENT_ID > 0) {
            $dataPay = $this->patientPaymentServices->get($this->PATIENT_PAYMENT_ID);
            if ($dataPay) {
                $this->CUSTOMER_ID = $dataPay->PATIENT_ID ?? 0;
                $this->DATE        = $dataPay->DATE ?? '';
                $this->LOCATION_ID = $dataPay->LOCATION_ID ?? '';
                $this->NOTES       = $dataPay->NOTES ?? '';
                $this->PO_NUMBER   = $dataPay->RECEIPT_REF_NO ?? '';
            }

        } else {

            $this->CUSTOMER_ID = 0;
            $this->DATE        = $this->userServices->getTransactionDateDefault();
            $this->LOCATION_ID = $this->userServices->getLocationDefault();
            $this->NOTES       = '';
            $this->PO_NUMBER   = '';
        }

        $this->LoadDropdown();
        $this->Modify                 = true;
        $this->ID                     = 0;
        $this->CODE                   = '';
        $this->SALES_REP_ID           = 0;
        $this->SHIP_VIA_ID            = $this->shipViaServices->getFirst();
        $this->CLASS_ID               = 0;
        $this->PAYMENT_TERMS_ID       = (int) $this->systemSettingServices->GetValue('DefaultPaymentTermsId');
        $this->DUE_DATE               = $this->paymentTermServices->getDueDate($this->PAYMENT_TERMS_ID);
        $this->AMOUNT                 = 0;
        $this->BALANCE_DUE            = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivables');
        $this->STATUS                 = 0;
        $this->OUTPUT_TAX_ID          = (int) $this->systemSettingServices->GetValue('OutputTaxId');
        $this->OUTPUT_TAX_RATE        = 0;
        $this->OUTPUT_TAX_AMOUNT      = 0;
        $this->OUTPUT_TAX_VAT_METHOD  = 0;
        $this->OUTPUT_TAX_ACCOUNT_ID  = 0;
        $this->TAXABLE_AMOUNT         = 0;
        $this->NONTAXABLE_AMOUNT      = 0;
        $this->STATUS_DESCRIPTION     = "";
        $this->DISCOUNT_DATE          = null;
        $this->DISCOUNT_PCT           = 0;
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
                        'CUSTOMER_ID'            => 'required|not_in:0|exists:contact,id',
                        'OUTPUT_TAX_ID'          => 'required|not_in:0',
                        'DATE'                   => 'required|date_format:Y-m-d',
                        'LOCATION_ID'            => 'required|not_in:0|exists:location,id',
                        'PAYMENT_TERMS_ID'       => 'required|',
                        'ACCOUNTS_RECEIVABLE_ID' => 'required|exists:account,id',
                        'OUTPUT_TAX_ACCOUNT_ID'  => 'required|exists:account,id',
                    ],
                    [],
                    [
                        'CUSTOMER_ID'            => 'Customer',
                        'OUTPUT_TAX_ID'          => 'Tax',
                        'DATE'                   => 'Date',
                        'LOCATION_ID'            => 'Location',
                        'PAYMENT_TERMS_ID'       => 'Payment Terms',
                        'ACCOUNTS_RECEIVABLE_ID' => 'Accounts Receivable',
                        'OUTPUT_TAX_ACCOUNT_ID'  => 'Output Tax Accounts',
                    ]
                );

                if (! $this->contactServices->isActive($this->CUSTOMER_ID)) {
                    session()->flash('error', 'Customer is not active');
                    return;
                }

                if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                    session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                    return;
                }

                DB::beginTransaction();
                $this->getTax();
                $this->ID = (int) $this->invoiceServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->PO_NUMBER,
                    0,
                    $this->SHIP_VIA_ID,
                    $this->SHIP_DATE,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->DISCOUNT_DATE,
                    $this->DISCOUNT_PCT,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->STATUS,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID
                );

                if ($this->PATIENT_PAYMENT_ID > 0) {
                    $this->getPatientItemAutoSave();
                    $this->patientPaymentServices->CustomerRef($this->PATIENT_PAYMENT_ID, true, $this->ID);
                    $this->invoiceServices->getUpdateTaxItem($this->ID, $this->OUTPUT_TAX_ID);
                    $getResult = $this->invoiceServices->ReComputed($this->ID);
                    $this->getPosted();
                }
                DB::commit();
                if ($this->IS_MODAL) {
                    $data = $this->invoiceServices->get($this->ID);
                    if ($data) {
                        $this->getInfo($data);
                    }

                    $this->Modify = false;
                    return;
                }
                return Redirect::route('customersinvoice_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->validate(
                    [
                        'CUSTOMER_ID'            => 'required|not_in:0',
                        'CODE'                   => 'required|max:20|unique:invoice,code,' . $this->ID,
                        'OUTPUT_TAX_ID'          => 'required|not_in:0',
                        'DATE'                   => 'required',
                        'LOCATION_ID'            => 'required',
                        'PAYMENT_TERMS_ID'       => 'required',
                        'ACCOUNTS_RECEIVABLE_ID' => 'required|exists:account,id',
                        'OUTPUT_TAX_ACCOUNT_ID'  => 'required|exists:account,id',
                    ],
                    [],
                    [
                        'CUSTOMER_ID'            => 'Petient',
                        'CODE'                   => 'Reference No.',
                        'OUTPUT_TAX_ID'          => 'Tax',
                        'DATE'                   => 'Date',
                        'LOCATION_ID'            => 'Location',
                        'PAYMENT_TERMS_ID'       => 'Payment Terms',
                        'ACCOUNTS_RECEIVABLE_ID' => 'Accounts Receivable',
                        'OUTPUT_TAX_ACCOUNT_ID'  => 'Output Tax Accounts',
                    ]
                );

                DB::beginTransaction();

                $data = $this->invoiceServices->Get($this->ID);

                if ($data) {
                    if ($this->STATUS == 16) {
                        $JNO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $this->ID);
                        if ($JNO > 0) {
                            // ACCOUNTS_RECEIVABLE_ID
                            $this->accountJournalServices->AccountSwitch(
                                $this->ACCOUNTS_RECEIVABLE_ID,
                                $data->ACCOUNTS_RECEIVABLE_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->CUSTOMER_ID,
                                $this->ID,
                                $this->invoiceServices->object_type_invoice,
                                $this->DATE,
                                0
                            );
                            // OUTPUT_TAX_ACCOUNT_ID

                            $this->accountJournalServices->AccountSwitch(
                                $this->OUTPUT_TAX_ACCOUNT_ID,
                                $data->OUTPUT_TAX_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->CUSTOMER_ID,
                                $this->ID,
                                $this->invoiceServices->object_type_invoice,
                                $this->DATE,
                                1
                            );
                        }
                    }
                }

                $this->getTax();
                $this->invoiceServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->PO_NUMBER,
                    0,
                    $this->SHIP_VIA_ID,
                    $this->SHIP_DATE,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->DISCOUNT_DATE,
                    $this->DISCOUNT_PCT,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->STATUS,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID
                );

                $this->invoiceServices->getUpdateTaxItem($this->ID, $this->OUTPUT_TAX_ID);
                $getResult = $this->invoiceServices->ReComputed($this->ID);
                DB::commit();
                $this->getUpdateAmount($getResult);
                session()->flash('message', 'Successfully updated');
            }

            $data = $this->invoiceServices->get($this->ID);

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

    public function getPatientItemAutoSave()
    {
        if ($this->PATIENT_PAYMENT_ID > 0) {
            $dataPay = $this->patientPaymentServices->get($this->PATIENT_PAYMENT_ID);
            if ($dataPay) {
                if ($this->patientPaymentServices->PHILHEALTH_METHOD_ID == $dataPay->PAYMENT_METHOD_ID) {

                    $PHILHEALTH_ID = $this->philHealthServices->getPhilHealthIdbyPatientPayment($this->PATIENT_PAYMENT_ID);
                    $this->philHealthServices->setUpdateTwoId($PHILHEALTH_ID, $this->PATIENT_PAYMENT_ID, $this->ID);
                    $PhData = $this->philHealthServices->get($PHILHEALTH_ID);
                    if ($PhData) {
                        $NO_TREATMENT = $this->philHealthServices->getNumberOfTreatment(
                            $this->CUSTOMER_ID,
                            $this->LOCATION_ID,
                            $PhData->DATE_ADMITTED,
                            $PhData->DATE_DISCHARGED
                        );

                        // get Service Charge

                        $RATE = $this->serviceChargeServices->getPhilHealthItem($PhData->DATE_ADMITTED, $this->LOCATION_ID, $this->CUSTOMER_ID);

                        //philheatlh
                        $this->invoiceServices->ItemStore(
                            $this->ID,
                            $this->patientPaymentServices->PHILHEALTH_ITEM,
                            $NO_TREATMENT,
                            0,
                            1,
                            $RATE,
                            0,
                            $NO_TREATMENT * $RATE,
                            0,
                            0,
                            0,
                            0,
                            0,
                            $dataPay->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                            0,
                            0,
                            0,
                            false,
                            false,
                            0
                        );
                    }
                } else {
                    // GL ENTRY
                    $this->invoiceServices->ItemStore(
                        $this->ID,
                        $this->patientPaymentServices->GL_ITEM,
                        1,
                        0,
                        1,
                        $dataPay->AMOUNT,
                        0,
                        $dataPay->AMOUNT,
                        0,
                        0,
                        0,
                        0,
                        0,
                        $dataPay->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                        0,
                        0,
                        0,
                        false,
                        false,
                        0
                    );
                }
            }
        }
    }

    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT            = $list['AMOUNT'];
            $this->BALANCE_DUE       = $list['BALANCE_DUE'];
            $this->OUTPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
            $this->TAXABLE_AMOUNT    = $list['TAXABLE_AMOUNT'];
            $this->NONTAXABLE_AMOUNT = $list['NONTAXABLE_AMOUNT'];
        }
    }
    #[On('update-status')]
    public function updateStatus()
    {
        $data = $this->invoiceServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function updateCancel()
    {
        $data = $this->invoiceServices->get($this->ID);
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
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->invoiceServices->document_type_id;
            $data            = $this->invoiceServices->ItemInventory($this->ID);

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

            $invoice      = (int) $this->invoiceServices->object_type_invoice;
            $invoiceItems = (int) $this->invoiceServices->object_type_invoice_item;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->invoiceServices->object_type_invoice, $this->ID) + 1;
            }

            //Main
            $invoiceData = $this->invoiceServices->getInvoiceJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceData, $this->LOCATION_ID, $invoice, $this->DATE);
            //Tax
            $invoiceDataTax = $this->invoiceServices->getInvoiceTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceDataTax, $this->LOCATION_ID, $invoice, $this->DATE);

            //Income
            $invoiceItemData = $this->invoiceServices->getInvoiceItemJournalIncome($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemData, $this->LOCATION_ID, $invoiceItems, $this->DATE);

            //cogs
            $invoiceItemCogs = $this->invoiceServices->getInvoiceItemJournalCogs($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemCogs, $this->LOCATION_ID, $invoiceItems, $this->DATE);

            //Income
            $invoiceItemAsset = $this->invoiceServices->getInvoiceItemJournalAsset($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemAsset, $this->LOCATION_ID, $invoiceItems, $this->DATE);

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

            $count = (int) $this->invoiceServices->CountItems($this->ID);
            if ($count == 0) {
                session()->flash('error', 'Item not found.');
                return;
            }

            DB::beginTransaction();

            if ($this->contactServices->IsNotPatient($this->CUSTOMER_ID)) {
                if (! $this->ItemInventory()) {
                    DB::rollBack();
                    return;
                }
            }

            if (! $this->AccountJournal()) {
                DB::rollBack();
                return;
            }

            $this->invoiceServices->StatusUpdate($this->ID, 15);
            DB::commit();
            Redirect::route('customersinvoice_edit', $this->ID)->with('message', 'Successfully posted');
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
            $this->invoiceServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('customersinvoice_edit', $this->ID)->with('message', 'Successfully Unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }

    private function deleteItem(int $Id, $INVOICE_ID, $JOURNAL_NO)
    {
        $invoiceDate = $this->invoiceServices->get($INVOICE_ID);
        if ($invoiceDate) {
            $invoiceItemData = $this->invoiceServices->ItemGet($Id, $INVOICE_ID);
            if ($invoiceItemData) {
                // Inventory
                $this->itemInventoryServices->InventoryModify(
                    $invoiceItemData->ITEM_ID,
                    $invoiceDate->LOCATION_ID,
                    $Id,
                    $this->invoiceServices->document_type_id,
                    $invoiceDate->DATE,
                    0,
                    0,
                    0
                );

                $this->itemInventoryServices->RecomputedOnhand(
                    $invoiceItemData->ITEM_ID,
                    $invoiceDate->LOCATION_ID,
                    $invoiceDate->DATE
                );

                // INCOME_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->INCOME_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    1,

                );
                // COGS_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->COGS_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    0,

                );
                // ASSET_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->ASSET_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    1,

                );
            }
        }
    }
    public function delete()
    {
        try {
            DB::beginTransaction();
            $data = $this->invoiceServices->get($this->ID);
            if ($data) {
                if ($data->STATUS == 15 || $data->STATUS == 16) {
                    //Main
                    $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $this->ID);
                    $this->accountJournalServices->DeleteJournal(
                        $data->ACCOUNTS_RECEIVABLE_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $this->ID,
                        $this->invoiceServices->object_type_invoice,
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
                        $this->invoiceServices->object_type_invoice,
                        $data->DATE,
                        1,

                    );
                    $dataitem = $this->invoiceServices->ItemView($this->ID);

                    foreach ($dataitem as $list) {
                        // delete Item
                        $this->deleteItem($list->ID, $this->ID, $JOURNAL_NO);
                    }
                }
            }

            // Delete main
            $this->invoiceServices->Delete($this->ID);
            $this->serviceChargeServices->RemovingUpdateInvoiceID($this->ID);
            DB::commit();
            return Redirect::route('customersinvoice')->with('message', 'Successfully deleted');

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.invoice.invoice-form');
    }
}
