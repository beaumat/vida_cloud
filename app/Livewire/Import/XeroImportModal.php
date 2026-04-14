<?php

namespace App\Livewire\Import;

use App\Enums\DocStatus;
use App\Models\ReceiveMoney;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use App\Services\ContactServices;
use App\Services\GeneralJournalServices;
use App\Services\InvoiceServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\PaymentTermServices;
use App\Services\ReceiveMoneyServices;
use App\Services\SpendMoneyServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\XeroDataServices;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class XeroImportModal extends Component
{

    public int $CONTACT_ID;
    public int $ID;
    public $accountList = [];
    public $ACCOUNT_ID;
    public $dataList = [];
    public $contactList = [];
    public $paymentMethodList = [];
    public $DATE;
    public $SOURCE_TYPE;
    public $REFERENCE;
    public $locationid = 0;
    public bool $showModal = false;
    private $xeroDataServices;
    private $contactServices;
    private $accountServices;
    public $DOC_TYPE = [];
    public int $DOC_ID;
    public string $DOC_NAME;
    public bool $isForwarded;
    private $billingServices;
    private $accountJournalServices;
    private $paymentTermServices;
    private $taxServices;
    private $systemSettingServices;
    private $billPaymentServices;
    private $invoiceServices;
    private $paymentServices;
    private $generalJournalServices;
    private $spendMoneyServices;
    private $receiveMoneyServices;

    public function boot(
        XeroDataServices $xero,
        ContactServices $contact,
        AccountServices $accountServices,
        BillingServices $billingServices,
        AccountJournalServices $accountJournalServices,
        PaymentTermServices $paymentTermServices,
        TaxServices $taxServices,
        SystemSettingServices $systemSettingServices,
        BillPaymentServices $billPaymentServices,
        InvoiceServices $invoiceServices,
        PaymentServices $paymentServices,
        GeneralJournalServices $generalJournalServices,
        SpendMoneyServices $spendMoneyServices,
        ReceiveMoneyServices $receiveMoneyServices

    ) {
        $this->xeroDataServices = $xero;
        $this->contactServices = $contact;
        $this->paymentTermServices = $paymentTermServices;
        $this->accountServices = $accountServices;
        $this->billingServices = $billingServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->taxServices = $taxServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->billPaymentServices = $billPaymentServices;
        $this->invoiceServices = $invoiceServices;
        $this->paymentServices = $paymentServices;
        $this->generalJournalServices = $generalJournalServices;
        $this->spendMoneyServices = $spendMoneyServices;
        $this->receiveMoneyServices = $receiveMoneyServices;

    }

    #[On('dataSend')]
    public function openModal($dataSend)
    {


        $this->CONTACT_ID = 0;
        $this->ACCOUNT_ID = 0;
        $this->contactList = [];
        $this->accountList = [];
        $this->isForwarded = $dataSend['is_forwarded'];
        $this->locationid = $dataSend['locationid'];

        if ($this->isForwarded == false) {
            $this->DATE = $dataSend['DATE'];
            $this->SOURCE_TYPE = $dataSend['SOURCE_TYPE'];
            $this->REFERENCE = $dataSend['REFERENCE'];


            $this->dataList = $this->xeroDataServices->callReference(
                $this->REFERENCE,
                $this->DATE,
                $this->SOURCE_TYPE,
                $this->locationid
            );

        } else {

            $this->dataList = $this->xeroDataServices->callReference(
                '',
                '',
                0,
                $this->locationid
            );
            $this->REFERENCE = 'F' . $this->locationid . '20231231';
            $this->DATE = '2023-12-31';
            $this->SOURCE_TYPE = 23;

        }


        foreach ($this->dataList as $data) {
            $this->DOC_TYPE = $this->xeroDataServices->DocumentType($this->isForwarded ? 0 : $data->SOURCE_TYPE);
            break;
        }

        $this->DOC_NAME = $this->DOC_TYPE['NAME'];
        $this->DOC_ID = (int) $this->DOC_TYPE['ID'];

        if ($this->isForwarded == false) {


            switch ($this->DOC_ID) {
                case 1:
                    $this->contactList = $this->contactServices->getListAllType();
                    break;
                case 2:
                    $this->accountList = $this->accountServices->getBankAccount();
                    $this->contactList = $this->contactServices->getListAllType();
                    break;
                case 10;
                    $this->contactList = $this->contactServices->getListAllType();
                    break;
                case 11;
                    $this->accountList = $this->accountServices->getBankAccount();
                    $this->contactList = $this->contactServices->getListAllType();
                    break;

                case 23;

                    $this->contactList = $this->contactServices->getListAllType();
                    break;

                case 35:
                    $this->accountList = $this->accountServices->getBankAccount();
                    break;

                case 36:
                    $this->accountList = $this->accountServices->getBankAccount();
                    break;
                default:
            }
        }
        $this->showModal = true;
    }
    private function AccountJournalBilling(): bool
    {
        try {

            $bills = (int) $this->billingServices->object_type_map_bill;
            $billItems = (int) $this->billingServices->object_type_map_bill_item;
            $billExpenses = (int) $this->billingServices->object_type_map_bill_expenses;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billingServices->object_type_map_bill, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->billingServices->object_type_map_bill, $this->ID) + 1;
            }

            //Item
            $billCreditItemData = $this->billingServices->getBillItemJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditItemData, $this->locationid, $billItems, $this->DATE, "ASSET");
            //Expenses
            $billCreditExpensesData = $this->billingServices->getBillExpenseJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditExpensesData, $this->locationid, $billExpenses, $this->DATE, "EXPENSE");


            //Main
            $billData = $this->billingServices->getBillJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billData, $this->locationid, $bills, $this->DATE, "AP");

            //Tax
            $billDataTax = $this->billingServices->getBillTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billDataTax, $this->locationid, $bills, $this->DATE, "TAX");

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum = (float) $data['DEBIT'];
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
    private function getPostedBilling()
    {
        try {

            $count_expense = (int) $this->billingServices->CountItems($this->ID, false);
            $count = $count_expense;
            if ($count == 0) {
                session()->flash('error', 'Item not found.');
                return false;
            }
            if (!$this->AccountJournalBilling()) {
                return false;

            }

            $this->billingServices->StatusUpdate($this->ID, (int)DocStatus::POSTED);
            return true;
        } catch (\Exception $e) {

            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    private function billEntry()
    {
        $this->validate([
            'CONTACT_ID' => 'required|exists:contact,id',
            'locationid' => 'required|exists:location,id'
        ], [], [
            'CONTACT_ID' => 'Contact',
            'locationid' => 'Location'
        ]);
        DB::beginTransaction();
        try {
            $AP_ID = 21;
            $DUE_DATE = $this->paymentTermServices->getDueDate(1, $this->DATE);
            $InputTaxId = (int) $this->systemSettingServices->GetValue('InputTaxId');
            $INPUT_TAX_RATE = 0;
            $INPUT_TAX_VAT_METHOD = 0;
            $INPUT_TAX_ACCOUNT_ID = 0;

            $tax = $this->taxServices->get($InputTaxId);
            if ($tax) {
                $INPUT_TAX_RATE = (float) $tax->RATE;
                $INPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
                $INPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
            }
            $BILL_ID = $this->billingServices->Store($this->REFERENCE, $this->DATE, $this->CONTACT_ID, $this->locationid, 1, $DUE_DATE, '', 0, '', $AP_ID, $InputTaxId, $INPUT_TAX_RATE, 0, $INPUT_TAX_VAT_METHOD, $INPUT_TAX_ACCOUNT_ID, 0);
            $this->ID = $BILL_ID;

            foreach ($this->dataList as $data) {
                $ACCOUNT_ID = $this->accountServices->getAccountNameIntoId($data->ACCOUNT);
                if ($ACCOUNT_ID <> $AP_ID && $ACCOUNT_ID > 0) {
                    if ($data->CREDIT > 0) {
                        $ID = $this->billingServices->ExpenseStore($BILL_ID, $ACCOUNT_ID, -1 * (float) $data->CREDIT, false, 0, 0, $data->DESCRIPTION, 0);
                        $this->xeroDataServices->updatePosted($data->ID, $ID, $this->billingServices->object_type_map_bill_expenses);
                    } else {
                        $ID = $this->billingServices->ExpenseStore($BILL_ID, $ACCOUNT_ID, (float) $data->DEBIT, false, 0, 0, $data->DESCRIPTION, 0);
                        $this->xeroDataServices->updatePosted($data->ID, $ID, $this->billingServices->object_type_map_bill_expenses);

                    }
                } else {
                    // Maybe ACCOUNT PAYABLE ONLY
                    $this->xeroDataServices->updatePosted($data->ID, $BILL_ID, $this->billingServices->object_type_map_bill);
                    $this->billingServices->updateXero($BILL_ID, true);
                }
            }
            $this->billingServices->ReComputed($BILL_ID);

            if ($this->getPostedBilling()) {
                DB::commit();
                $this->closeModal();
            } else {
                DB::rollBack();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());

        }
    }
    private function getPostedBillPayment()
    {

        $check = $this->billPaymentServices->object_type_check;
        $checkbills = $this->billPaymentServices->object_type_check_bills;
        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($check, $this->ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($check, $this->ID) + 1;
        }

        $checkDataBills = $this->billPaymentServices->billPaymentBillsJournal($this->ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $checkDataBills,
            $this->locationid,
            $checkbills,
            $this->DATE,
            "AP"
        );

        $checkData = $this->billPaymentServices->billPaymentJournalRemaining($this->ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $checkData,
            $this->locationid,
            $check,
            $this->DATE,
            "BILL"
        );

        $checkData = $this->billPaymentServices->billPaymentJournal($this->ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $checkData,
            $this->locationid,
            $check,
            $this->DATE,
            "BILL"
        );

        $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

        $debit_sum = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum) {
            $this->billPaymentServices->StatusUpdate($this->ID, 15);
            session()->flash('message', 'Successfully posted');
            return true;
        }
        session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
        return false;

    }
    private function billPayment()
    {
        $this->validate([
            'CONTACT_ID' => 'required|exists:contact,id',
            'locationid' => 'required|exists:location,id',
            'ACCOUNT_ID' => 'required|exists:account,id'

        ], [], [
            'CONTACT_ID' => 'Contact',
            'locationid' => 'Location',
            'ACCOUNT_ID' => 'Account'
        ]);



        try {
            DB::beginTransaction();
            $BANK_ACCOUNT_ID = $this->ACCOUNT_ID;
            $BILL_PAY_ID = (int) $this->billPaymentServices->Store($this->REFERENCE, $this->DATE, $BANK_ACCOUNT_ID, $this->CONTACT_ID, $this->locationid, 0, "", 21);
            $this->ID = $BILL_PAY_ID;
            foreach ($this->dataList as $data) {
                $ACCOUNT_ID = $this->accountServices->getAccountNameIntoId($data->ACCOUNT);
                if ($ACCOUNT_ID <> $BANK_ACCOUNT_ID && $ACCOUNT_ID > 0) {
                    $BILL_ID = $this->billingServices->getBillByXero($this->REFERENCE);
                    if ($BILL_ID == 0) {
                        session()->flash('error', 'Bill not found.');
                        DB::rollBack();
                        return;
                    }

                    $ID = $this->billPaymentServices->billPaymentBills_Store(
                        $BILL_PAY_ID,
                        $BILL_ID,
                        0,
                        (float) $data->DEBIT,
                        0,
                        $ACCOUNT_ID
                    );
                    $this->xeroDataServices->updatePosted($data->ID, $ID, $this->billPaymentServices->object_type_check_bills);
                    $this->billingServices->UpdateBalance($BILL_ID);

                } else {

                    $this->xeroDataServices->updatePosted($data->ID, $BILL_PAY_ID, $this->billPaymentServices->object_type_check);
                    $this->billPaymentServices->updateXero($BILL_PAY_ID, true, (float) $data->CREDIT);
                }
                // update amount
                $this->billPaymentServices->UpdateBillPaymentApplied($BILL_PAY_ID);
            }
            if ($this->getPostedBillPayment() == true) {
                DB::commit();
                $this->closeModal();
            } else {
                DB::rollBack();
            }

        } catch (\Throwable $th) {
            //throw $th;
            session()->flash('error', 'Error: ' . $th->getMessage());
            DB::rollBack();
        }
    }
    private function AccountJournalInvoice(): bool
    {
        try {

            $invoice = (int) $this->invoiceServices->object_type_invoice;
            $invoiceItems = (int) $this->invoiceServices->object_type_invoice_item;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->invoiceServices->object_type_invoice, $this->ID) + 1;
            }

            //Main
            $invoiceData = $this->invoiceServices->getInvoiceJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceData, $this->locationid, $invoice, $this->DATE);
            //Tax
            $invoiceDataTax = $this->invoiceServices->getInvoiceTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceDataTax, $this->locationid, $invoice, $this->DATE);

            //Income
            $invoiceItemData = $this->invoiceServices->getInvoiceItemJournalIncome($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemData, $this->locationid, $invoiceItems, $this->DATE);

            //cogs
            $invoiceItemCogs = $this->invoiceServices->getInvoiceItemJournalCogs($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemCogs, $this->locationid, $invoiceItems, $this->DATE);

            //Income
            $invoiceItemAsset = $this->invoiceServices->getInvoiceItemJournalAsset($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemAsset, $this->locationid, $invoiceItems, $this->DATE);

            //Checking if balance
            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum = (float) $data['DEBIT'];
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
    public function getPostedInvoice()
    {


        $count = (int) $this->invoiceServices->CountItems($this->ID);
        if ($count == 0) {
            session()->flash('error', 'Item not found.');
            return false;
        }

        if (!$this->AccountJournalInvoice()) {
            session()->flash('error', 'Error occurred: Journal not balance');
            return false;
        }

        $this->invoiceServices->StatusUpdate($this->ID, 15);
        return true;


    }
    private function InvoiceEntry()
    {
        $this->validate([
            'CONTACT_ID' => 'required|exists:contact,id',
            'locationid' => 'required|exists:location,id'
        ], [], [
            'CONTACT_ID' => 'Contact',
            'locationid' => 'Location'
        ]);


        $AR_ID = 4;
        $DUE_DATE = $this->paymentTermServices->getDueDate(1, $this->DATE);
        $OutputTaxId = (int) $this->systemSettingServices->GetValue('OutputTaxId');
        $OUTPUT_TAX_RATE = 0;
        $OUTPUT_TAX_VAT_METHOD = 0;
        $OUTPUT_TAX_ACCOUNT_ID = 0;

        $tax = $this->taxServices->get($OutputTaxId);
        if ($tax) {
            $OUTPUT_TAX_RATE = (float) $tax->RATE;
            $OUTPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $OUTPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
        DB::beginTransaction();
        try {


            $INVOICE_ID = (int) $this->invoiceServices->Store($this->REFERENCE, $this->DATE, $this->CONTACT_ID, $this->locationid, 0, 0, '', 0, 0, null, 1, $DUE_DATE, null, 0, '', $AR_ID, 0, $OutputTaxId, $OUTPUT_TAX_RATE, $OUTPUT_TAX_VAT_METHOD, $OUTPUT_TAX_ACCOUNT_ID);

            $this->ID = $INVOICE_ID;

            foreach ($this->dataList as $data) {
                $ACCOUNT_ID = $this->accountServices->getAccountNameIntoId($data->ACCOUNT);
                if ($ACCOUNT_ID == 0) {
                    session()->flash('error', 'Account not found.');
                    DB::rollBack();
                    return;
                }

                if ($ACCOUNT_ID <> $AR_ID && $ACCOUNT_ID > 0) {
                    if ($data->CREDIT > 0) {
                        $ID = $this->invoiceServices->ItemStore($INVOICE_ID, 420, 1, 0, (float) $data->CREDIT, (float) $data->CREDIT, 0, (float) $data->CREDIT, false, 0, 0, 0, 0, $ACCOUNT_ID, 0, 0, 0, 0, 0, 0);
                        $this->xeroDataServices->updatePosted($data->ID, $ID, $this->invoiceServices->object_type_invoice_item);
                    }
                } else {
                    // Maybe ACCOUNT RECEIVABLE ONLY
                    $this->xeroDataServices->updatePosted($data->ID, $INVOICE_ID, $this->invoiceServices->object_type_invoice);
                    $this->invoiceServices->updateXero($INVOICE_ID, true);
                }

            }
            $this->invoiceServices->ReComputed($INVOICE_ID);
            if ($this->getPostedInvoice() == true) {
                DB::commit();
                $this->closeModal();
            } else {
                DB::rollBack();
            }

        } catch (\Throwable $th) {
            //throw $th;

            DB::rollBack();
            session()->flash('error', 'Error: ' . $th->getMessage());
        }
    }
    private function getPostedInvoicePayment(): bool
    {

        $payment = $this->paymentServices->object_type_payment;
        $paymentInvoicesId = $this->paymentServices->object_type_payment_invoices;

        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($payment, $this->ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($payment, $this->ID) + 1;
        }

        $paymentData = $this->paymentServices->PaymentJournal($this->ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $paymentData, $this->locationid, $payment, $this->DATE, "UF");
        $paymentDataR = $this->paymentServices->PaymentJournalRemaining($this->ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $paymentDataR, $this->locationid, $payment, $this->DATE, "A/R");
        $paymentInvoiceData = $this->paymentServices->PaymentInvoicejournal($this->ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $paymentInvoiceData, $this->locationid, $paymentInvoicesId, $this->DATE, "A/R");

        $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
        $debit_sum = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum) {
            $this->paymentServices->StatusUpdate($this->ID, 15);

            return true;
        }

        return false;

    }
    private function InvoicePayment()
    {
        $this->validate([
            'CONTACT_ID' => 'required|exists:contact,id',
            'locationid' => 'required|exists:location,id',
            'ACCOUNT_ID' => 'required|exists:account,id'

        ], [], [
            'CONTACT_ID' => 'Contact',
            'locationid' => 'Location',
            'ACCOUNT_ID' => 'Account'
        ]);


        DB::beginTransaction();
        try {

            $BANK_ACCOUNT_ID = (int) $this->ACCOUNT_ID;
            $PAYMENT_METHOD_ID = 5;
            $PAYMENT_ID = $this->paymentServices->Store($this->REFERENCE, $this->DATE, $this->CONTACT_ID, $this->locationid, 0, 0, $PAYMENT_METHOD_ID, '', null, '', null, '', $BANK_ACCOUNT_ID, 0, 0, 4, 0);
            foreach ($this->dataList as $data) {
                $ACCOUNT_ID = $this->accountServices->getAccountNameIntoId($data->ACCOUNT);
                if ($ACCOUNT_ID <> $BANK_ACCOUNT_ID && $ACCOUNT_ID > 0) {
                    $INVOICE_ID = $this->invoiceServices->getInvoiceByXero($this->REFERENCE);
                    if ($INVOICE_ID == 0) {
                        session()->flash('error', 'Invoice not found.');
                        DB::rollBack();
                        return;
                    }

                    $ID = (int) $this->paymentServices->paymentInvoiceStore($PAYMENT_ID, $INVOICE_ID, 0, (float) $data->CREDIT, 0, $ACCOUNT_ID);
                    $this->xeroDataServices->updatePosted($data->ID, $ID, $this->paymentServices->object_type_payment);
                    $this->invoiceServices->updateInvoiceBalance($INVOICE_ID);

                } else {

                    $this->xeroDataServices->updatePosted($data->ID, $PAYMENT_ID, $this->paymentServices->object_type_payment_invoices);
                    $this->paymentServices->updateXero($PAYMENT_ID, true, (float) $data->DEBIT);
                }
            }
            if ($this->getPostedInvoicePayment() == true) {
                DB::commit();
                $this->closeModal();
            } else {
                DB::rollBack();
            }

        } catch (\Throwable $th) {

            DB::rollBack();
            session()->flash('error', 'Error: ' . $th->getMessage());
        }
    }
    private function AccountJournalGeneralJournal(): bool
    {

        $generaljournal = $this->generalJournalServices->object_type_general_journal_details_id;
        $getFirstId = (int) $this->generalJournalServices->getFirstDetailsID($this->ID);
        $JOURNAL_NO = $this->accountJournalServices->getRecord($generaljournal, $getFirstId);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($generaljournal, $getFirstId) + 1;
        }

        //Main
        $generalJournalData = $this->generalJournalServices->getGeneralJournalEntries($this->ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $generalJournalData, $this->locationid, $generaljournal, $this->DATE);
        $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

        $debit_sum = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum && $debit_sum > 0 && $credit_sum > 0) {
            return true;
        }
        if ($this->isForwarded) {
            return true;
        }

        return false;

    }
    private function postedGeneralJournal(): bool
    {

        $total_result = $this->generalJournalServices->GetTotal($this->ID);
        $total_debit = (float) $total_result['TOTAL_DEBIT'];
        $total_credit = (float) $total_result['TOTAL_CREDIT'];
        if ($total_debit == 0) {
            Session()->flash('error', 'No debit entry');
            return false;
        }
        if ($total_credit == 0) {
            Session()->flash('error', 'No credit entry');
            return false;
        }

        if ($total_debit == $total_credit || $this->isForwarded == true) {
            if (!$this->AccountJournalGeneralJournal()) {
                return false;
            }
            $this->generalJournalServices->StatusUpdate($this->ID, 15);

            return true;
        }

        return false;

    }
    private function GeneralJournal()
    {

        if (!$this->isForwarded) {
            $this->validate([
                'CONTACT_ID' => 'required|exists:contact,id',
                'locationid' => 'required|exists:location,id'
            ], [], [
                'CONTACT_ID' => 'Contact',
                'locationid' => 'Location'
            ]);
        }



        DB::beginTransaction();
        try {
            //code...

            $GJ_ID = $this->generalJournalServices->Store($this->DATE, $this->REFERENCE, $this->locationid, false, $this->isForwarded ? 'Balance Forwarded' : '', $this->CONTACT_ID, );
            $this->ID = $GJ_ID;
            foreach ($this->dataList as $data) {
                $ACCOUNT_ID = $this->accountServices->getAccountNameIntoId($data->ACCOUNT);
                if ($ACCOUNT_ID > 0) {

                    $NOTES = $this->isForwarded ? 'Balance Forwarded' : $data->DESCRIPTION;
                    $ID = $this->generalJournalServices->StoreDetails($GJ_ID, $ACCOUNT_ID, (float) $data->DEBIT, (float) $data->CREDIT, $NOTES, 0);
                    $this->xeroDataServices->updatePosted($data->ID, $ID, $this->generalJournalServices->object_type_general_journal_details_id);

                }
            }

            $this->generalJournalServices->updateIsXero($GJ_ID, true);

            if ($this->postedGeneralJournal() == true) {
                DB::commit();
                $this->closeModal();
            } else {
                DB::rollBack();
            }
        } catch (\Throwable $th) {
            //throw $th;

            DB::rollBack();
            session()->flash('error', 'Error: ' . $th->getMessage());
        }
    }
    private function AccountJournalSpendMoney(): bool
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->spendMoneyServices->object_type_map_spend_money, $this->ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->spendMoneyServices->object_type_map_spend_money, $this->ID) + 1;
        }
        //Main
        $main = $this->spendMoneyServices->JournalEntry($this->ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $main,
            $this->locationid,
            $this->spendMoneyServices->object_type_map_spend_money,
            $this->DATE
        );

        //Details
        $details = $this->spendMoneyServices->JournalEntryDetails($this->ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $details,
            $this->locationid,
            $this->spendMoneyServices->object_type_map_spend_money_details,
            $this->DATE
        );

        $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

        $debit_sum = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum && $debit_sum > 0 && $credit_sum > 0) {
            return true;
        }
        session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
        return false;

    }
    public function postedSpendMoney()
    {
        $detailsList = $this->spendMoneyServices->getDetailsList($this->ID);
        if ($detailsList) {

            if (!$this->AccountJournalSpendMoney()) {
                return false;
            }
            $this->spendMoneyServices->StatusUpdate($this->ID, 15);

            return true;
        }
        return false;
    }
    private function SpendMoney()
    {
        $this->validate([

            'locationid' => 'required|exists:location,id',
            'ACCOUNT_ID' => 'required|exists:account,id'

        ], [], [

            'locationid' => 'Location',
            'ACCOUNT_ID' => 'Account'
        ]);
        DB::beginTransaction();

        try {

            $SPEND_MONEY_ID = (int) $this->spendMoneyServices->Store($this->DATE, $this->REFERENCE, $this->locationid, $this->ACCOUNT_ID, '', true);
            $this->ID = $SPEND_MONEY_ID;

            foreach ($this->dataList as $data) {
                $ACCOUNT_ID = $this->accountServices->getAccountNameIntoId($data->ACCOUNT);
                if ($ACCOUNT_ID > 0 && (float) $data->DEBIT > 0) {
                    $ID = $this->spendMoneyServices->StoreDetails($SPEND_MONEY_ID, $ACCOUNT_ID, (float) $data->DEBIT, $data->DESCRIPTION);
                    $this->xeroDataServices->updatePosted($data->ID, $ID, $this->spendMoneyServices->object_type_map_spend_money_details);

                } else {
                    $this->xeroDataServices->updatePosted($data->ID, $SPEND_MONEY_ID, $this->spendMoneyServices->object_type_map_spend_money);

                }
            }
            $this->spendMoneyServices->ReCalculate($SPEND_MONEY_ID);

            if ($this->postedSpendMoney() == true) {
                DB::commit();
                $this->closeModal();
            } else {
                DB::rollBack();
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            session()->flash('error', 'Error: ' . $th->getMessage());
        }

    }
    private function AccountJournalReceiveMoney(): bool
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->receiveMoneyServices->object_type_map_receive_money, $this->ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->receiveMoneyServices->object_type_map_receive_money, $this->ID) + 1;
        }
        //Main
        $main = $this->receiveMoneyServices->JournalEntry($this->ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $main,
            $this->locationid,
            $this->receiveMoneyServices->object_type_map_receive_money,
            $this->DATE
        );

        //Details
        $details = $this->receiveMoneyServices->JournalEntryDetails($this->ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $details,
            $this->locationid,
            $this->receiveMoneyServices->object_type_map_receive_money_details,
            $this->DATE
        );

        $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

        $debit_sum = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum && $debit_sum > 0 && $credit_sum > 0) {
            return true;
        }

        return false;

    }
    public function postedReceiveMoney()
    {

        $detailsList = $this->receiveMoneyServices->getDetailsList($this->ID);

        if ($detailsList) {

            if (!$this->AccountJournalReceiveMoney()) {
                return false;
            }
            $this->receiveMoneyServices->StatusUpdate($this->ID, 15);

            return true;
        }

        return false;

    }
    private function ReceiveMoney()
    {
        $this->validate([

            'locationid' => 'required|exists:location,id',
            'ACCOUNT_ID' => 'required|exists:account,id'

        ], [], [

            'locationid' => 'Location',
            'ACCOUNT_ID' => 'Account'
        ]);

        DB::beginTransaction();

        try {
            $RECEIVE_MONEY_ID = (int) $this->receiveMoneyServices->Store($this->DATE, $this->REFERENCE, $this->locationid, $this->ACCOUNT_ID, '', true);
            $this->ID = $RECEIVE_MONEY_ID;
            foreach ($this->dataList as $data) {
                $ACCOUNT_ID = $this->accountServices->getAccountNameIntoId($data->ACCOUNT);
                if ($ACCOUNT_ID > 0 && (float) $data->CREDIT > 0) {
                    $ID = $this->receiveMoneyServices->StoreDetails($RECEIVE_MONEY_ID, $ACCOUNT_ID, (float) $data->CREDIT, $data->DESCRIPTION);
                    $this->xeroDataServices->updatePosted($data->ID, $ID, $this->receiveMoneyServices->object_type_map_receive_money_details);

                } else {
                    $this->xeroDataServices->updatePosted($data->ID, $RECEIVE_MONEY_ID, $this->receiveMoneyServices->object_type_map_receive_money);
                }
            }
            $this->receiveMoneyServices->ReCalculate($RECEIVE_MONEY_ID);
            if ($this->postedReceiveMoney() == true) {
                DB::commit();
                $this->closeModal();
            } else {
                DB::rollBack();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $th->getMessage());
        }
    }
    public function save()
    {
        switch ($this->DOC_ID) {
            case 1: // bill
                $this->billEntry();
                break;
            case 2: // bill payment
                $this->billPayment();
                break;
            case 10: // invoice
                $this->InvoiceEntry();
                break;
            case 11: // invoice payment 
                $this->InvoicePayment();
                break;
            case 23:
                $this->GeneralJournal();
                break;
            case 35:
                $this->SpendMoney();
                break;
            case 36:
                $this->ReceiveMoney();
                break;
            default:
                # code...
                break;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.import.xero-import-modal');
    }
}
