<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentInvoices;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class PaymentServices
{
    use WithPagination;

    public int $object_type_payment          = 41;
    public int $object_type_payment_invoices = 42;
    private $object;
    private $dateServices;
    private $systemSettingServices;
    private $accountJournalServices;
    private $usersLogServices;
    public function __construct(
        ObjectServices $objectService,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        AccountJournalServices $accountJournalServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                 = $objectService;
        $this->dateServices           = $dateServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->usersLogServices       = $usersLogServices;
    }
    public function get($ID)
    {
        $result = Payment::where('ID', $ID)->first();
        return $result;
    }
    public function getViaUndeposit($ID)
    {
        $result = Payment::where('ID', $ID)->where('DEPOSITED', '=', '0')->first();
        if ($result) {
            return $result;
        }
        return [];
    }
    public function getTotalPay(int $INVOICE_ID, int $EXECPT_PAYMENT_ID): float
    {
        $data = PaymentInvoices::query()
            ->selectRaw('ifnull(sum(AMOUNT_APPLIED),0) as total')
            ->where('INVOICE_ID', $INVOICE_ID)
            ->where('PAYMENT_ID', '<>', $EXECPT_PAYMENT_ID)
            ->first();
        if ($data) {
            return $data->total;
        }

        return 0;
    }

    public function getTotalPayOrignal(int $PAYMENT_ID): float
    {
        $data = PaymentInvoices::query()
            ->selectRaw('ifnull(sum(AMOUNT_APPLIED),0) as total')
            ->where('PAYMENT_ID', $PAYMENT_ID)
            ->first();
        if ($data) {
            return $data->total;
        }

        return 0;
    }
    public function getUpdateUndeposit(int $ID, int $UNDEPOSITED_FUNDS_ACCOUNT_ID)
    {
        $data = $this->get($ID);
        if ($data) {

            Payment::where('ID', '=', $ID)
                ->update(['UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID]);

            return $data;
        }

        return null;

    }

    public function getUpdateReceiptNo(int $ID, string $RECEIPT_REF_NO)
    {
        Payment::where('ID', '=', $ID)
            ->update(['RECEIPT_REF_NO' => $RECEIPT_REF_NO]);

    }
    public function Store(
        string $CODE,
        $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        float $AMOUNT_APPLIED,
        int $PAYMENT_METHOD_ID,
        string $CARD_NO,
        $CARD_EXPIRY_DATE,
        string $RECEIPT_REF_NO,
        $RECEIPT_DATE,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OVERPAYMENT_ACCOUNT_ID,
        bool $DEPOSITED,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $PAYMENT_PERIOD_ID = 0
    ): int {

        $ID          = (int) $this->object->ObjectNextID('PAYMENT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PAYMENT');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Payment::create([
            'ID'                           => $ID,
            'RECORDED_ON'                  => $this->dateServices->Now(),
            'CODE'                         => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                         => $DATE,
            'CUSTOMER_ID'                  => $CUSTOMER_ID,
            'LOCATION_ID'                  => $LOCATION_ID,
            'AMOUNT'                       => $AMOUNT,
            'AMOUNT_APPLIED'               => $AMOUNT_APPLIED,
            'PAYMENT_METHOD_ID'            => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
            'CARD_NO'                      => $CARD_NO,
            'CARD_EXPIRY_DATE'             => $CARD_EXPIRY_DATE ?? null,
            'RECEIPT_REF_NO'               => $RECEIPT_REF_NO,
            'RECEIPT_DATE'                 => $RECEIPT_DATE ?? null,
            'NOTES'                        => $NOTES,
            'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID > 0 ? $UNDEPOSITED_FUNDS_ACCOUNT_ID : null,
            'OVERPAYMENT_ACCOUNT_ID'       => $OVERPAYMENT_ACCOUNT_ID > 0 ? $OVERPAYMENT_ACCOUNT_ID : null,
            'STATUS'                       => 0,
            'STATUS_DATE'                  => $this->dateServices->NowDate(),
            'DEPOSITED'                    => $DEPOSITED,
            'ACCOUNTS_RECEIVABLE_ID'       => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
            'PAYMENT_PERIOD_ID'            => $PAYMENT_PERIOD_ID > 0 ? $PAYMENT_PERIOD_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PAYMENT, $ID);

        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        int $PAYMENT_METHOD_ID,
        string $CARD_NO,
        $CARD_EXPIRY_DATE,
        string $RECEIPT_REF_NO,
        $RECEIPT_DATE,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OVERPAYMENT_ACCOUNT_ID,
        bool $DEPOSITED,
        int $ACCOUNTS_RECEIVABLE_ID
    ) {
        Payment::where('ID', $ID)
            ->update([
                'CODE'                         => $CODE,
                'DATE'                         => $DATE,
                'CUSTOMER_ID'                  => $CUSTOMER_ID,
                'LOCATION_ID'                  => $LOCATION_ID,
                'AMOUNT'                       => $AMOUNT,
                'PAYMENT_METHOD_ID'            => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
                'CARD_NO'                      => $CARD_NO,
                'CARD_EXPIRY_DATE'             => $CARD_EXPIRY_DATE ?? null,
                'RECEIPT_REF_NO'               => $RECEIPT_REF_NO,
                'RECEIPT_DATE'                 => $RECEIPT_DATE ?? null,
                'NOTES'                        => $NOTES,
                'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID > 0 ? $UNDEPOSITED_FUNDS_ACCOUNT_ID : null,
                'OVERPAYMENT_ACCOUNT_ID'       => $OVERPAYMENT_ACCOUNT_ID > 0 ? $OVERPAYMENT_ACCOUNT_ID : null,
                'DEPOSITED'                    => $DEPOSITED,
                'ACCOUNTS_RECEIVABLE_ID'       => $ACCOUNTS_RECEIVABLE_ID,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::PAYMENT, $ID);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Payment::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::PAYMENT, $ID);
    }
    public function Delete(int $ID)
    {
        PaymentInvoices::where('PAYMENT_ID', $ID)->delete();
        Payment::where('ID', $ID)->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PAYMENT, $ID);
    }
    public function Search($search, int $locationId, int $perPage)
    {
        $result = Payment::query()
            ->select([
                'payment.ID',
                'payment.CODE',
                'payment.DATE',
                'payment.AMOUNT',
                'payment.AMOUNT_APPLIED',
                'payment.NOTES',
                'payment.STATUS as STATUS_ID',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'pm.DESCRIPTION as PAYMENT_METHOD',

            ])
            ->join('contact as c', 'c.ID', '=', 'payment.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'payment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'payment.STATUS')
            ->join('payment_method as pm', 'pm.ID', '=', 'payment.PAYMENT_METHOD_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('payment.CODE', 'like', '%' . $search . '%')
                    ->orWhere('payment.AMOUNT_APPLIED', 'like', '%' . $search . '%')
                    ->orWhere('payment.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('payment.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function PaymentInvoiceGet(int $ID, int $PAYMENT_ID, int $INVOICE_ID)
    {
        $result = PaymentInvoices::where('ID', '=', $ID)
            ->where('PAYMENT_ID', '=', $PAYMENT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->first();

        return $result;
    }
    public function PaymentInvoiceStore(int $PAYMENT_ID, int $INVOICE_ID, float $DISCOUNT, float $AMOUNT_APPLIED, int $DISCOUNT_ACCOUNT_ID, int $ACCOUNTS_RECEIVABLE_ID): int
    {
        $ID = $this->object->ObjectNextID('PAYMENT_INVOICES');
        PaymentInvoices::create([
            'ID'                     => $ID,
            'PAYMENT_ID'             => $PAYMENT_ID,
            'INVOICE_ID'             => $INVOICE_ID,
            'DISCOUNT'               => $DISCOUNT > 0 ? $DISCOUNT : null,
            'AMOUNT_APPLIED'         => $AMOUNT_APPLIED,
            'DISCOUNT_ACCOUNT_ID'    => $DISCOUNT_ACCOUNT_ID > 0 ? $DISCOUNT_ACCOUNT_ID : null,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PAYMENT_INVOICES, $PAYMENT_ID);

        return $ID;
    }
    public function PaymentInvoiceExist(int $PAYMENT_ID, int $INVOICE_ID): int
    {
        $data = PaymentInvoices::where('PAYMENT_ID', '=', $PAYMENT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->first();

        if ($data) {
            return (int) $data->ID ?? 0;
        }
        return 0;
    }
    public function PaymentInvoiceUpdate(int $ID, int $PAYMENT_ID, int $INVOICE_ID, float $DISCOUNT, float $AMOUNT_APPLIED)
    {
        PaymentInvoices::where('ID', $ID)
            ->where('PAYMENT_ID', $PAYMENT_ID)
            ->where('INVOICE_ID', $INVOICE_ID)
            ->update([
                'DISCOUNT'       => $DISCOUNT,
                'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::PAYMENT_INVOICES, $PAYMENT_ID);
    }
    public function PaymentInvoiceDelete(int $ID, int $PAYMENT_ID, int $INVOICE_ID)
    {
        PaymentInvoices::where('ID', '=', $ID)
            ->where('PAYMENT_ID', '=', $PAYMENT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PAYMENT_INVOICES, $PAYMENT_ID);
    }
    public function getPaymentInvoiceDetails(int $ID)
    {
        $data = PaymentInvoices::where('ID', '=', $ID)->first();

        if ($data) {
            return $data;
        }

        return [];
    }
    public function PaymentInvoiceList(int $PAYMENT_ID)
    {
        return PaymentInvoices::query()
            ->select([
                'payment_invoices.ID',
                'payment_invoices.INVOICE_ID',
                'payment_invoices.AMOUNT_APPLIED',
                'payment_invoices.ACCOUNTS_RECEIVABLE_ID',
                'i.DATE',
                'i.CODE',
                'i.AMOUNT',
                'i.BALANCE_DUE',

            ])
            ->leftJoin('invoice as i', 'i.ID', '=', 'payment_invoices.INVOICE_ID')
            ->where('payment_invoices.PAYMENT_ID', $PAYMENT_ID)
            ->get();
    }

    public function UpdatePaymentApplied(int $PAYMENT_ID): float
    {
        $pay = PaymentInvoices::query()
            ->select(DB::raw('IFNULL(SUM(payment_invoices.AMOUNT_APPLIED), 0) as pay'))
            ->where('payment_invoices.PAYMENT_ID', '=', $PAYMENT_ID)
            ->first()
            ->pay;

        Payment::where('ID', $PAYMENT_ID)->update(['AMOUNT_APPLIED' => $pay]);

        return $pay;
    }
    public function InvoicePaymentList(int $INVOICE_ID, int $CUSTOMER_ID)
    {
        return Payment::query()
            ->select([
                'payment_invoices.ID',
                'payment_invoices.PAYMENT_ID',
                'payment.CODE',
                'payment.DATE',
                'payment.AMOUNT',
                'payment.NOTES',
                'payment.RECEIPT_REF_NO',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'payment_invoices.AMOUNT_APPLIED',
                'a.NAME as BANK_ACCOUNT',

            ])
            ->join('payment_method', 'payment_method.ID', '=', 'payment.PAYMENT_METHOD_ID')
            ->join('payment_invoices', 'payment_invoices.PAYMENT_ID', '=', 'payment.ID')
            ->leftJoin('account as a', 'a.ID', '=', 'payment.UNDEPOSITED_FUNDS_ACCOUNT_ID')
            ->where('payment_invoices.INVOICE_ID', '=', $INVOICE_ID)
            ->where('payment.CUSTOMER_ID', '=', $CUSTOMER_ID)
            ->get();
    }
    public function PaymentAvailableList(int $CUSTOMER_ID, int $LOCATION_ID)
    {
        $result = Payment::query()
            ->select([
                'payment.ID',
                'payment.CODE',
                'payment.DATE',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'payment.AMOUNT',
                'payment.AMOUNT_APPLIED',
            ])
            ->leftJoin('payment_method', 'payment_method.ID', '=', 'payment.PAYMENT_METHOD_ID')
            ->where('payment.CUSTOMER_ID', $CUSTOMER_ID)
            ->where('payment.LOCATION_ID', $LOCATION_ID)
            ->whereRaw('(payment.AMOUNT - payment.AMOUNT_APPLIED) > 0')
            ->get();

        return $result;
    }
    public function GetPaymentRemaining(int $PAYMENT_ID): float
    {
        $result = Payment::where('ID', $PAYMENT_ID)->first();
        return (float) $result->AMOUNT - (float) $result->AMOUNT_APPLIED;
    }
    public function HaveRemainingPaymentBalance(int $CUSTOMER_ID, int $LOCATION_ID): bool
    {
        $total = (float) Payment::query()
            ->select(DB::raw('IFNULL(SUM(AMOUNT-AMOUNT_APPLIED), 0) AS TOTAL'))
            ->where('CUSTOMER_ID', $CUSTOMER_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->first()
            ->TOTAL;

        if ($total > 0) {
            return true;
        }

        return false;
    }
    public function PaymentJournal(int $PAYMENT_ID)
    {
        $result = Payment::query()
            ->select([
                'ID',
                'UNDEPOSITED_FUNDS_ACCOUNT_ID as ACCOUNT_ID',
                'CUSTOMER_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $PAYMENT_ID)
            ->get();

        return $result;
    }
    public function PaymentJournalRemaining(int $PAYMENT_ID)
    {
        $result = Payment::query()
            ->select([
                'payment.ID',
                'payment.ACCOUNTS_RECEIVABLE_ID as ACCOUNT_ID',
                'payment.CUSTOMER_ID as SUBSIDIARY_ID',
                DB::raw("(payment.AMOUNT - (select IFNULL(sum(payment_invoices.AMOUNT_APPLIED),0)  from payment_invoices where payment_invoices.PAYMENT_ID = payment.ID limit 1)) as AMOUNT"),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('payment.ID', '=', $PAYMENT_ID)
            ->get();

        return $result;
    }
    public function PaymentInvoicejournal(int $PAYMENT_ID)
    {
        $result = PaymentInvoices::query()
            ->select([
                'payment_invoices.ID',
                'payment_invoices.ACCOUNTS_RECEIVABLE_ID as ACCOUNT_ID',
                'payment_invoices.INVOICE_ID as SUBSIDIARY_ID',
                'payment_invoices.AMOUNT_APPLIED as AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])->join('payment', 'payment.ID', '=', 'payment_invoices.PAYMENT_ID')
            ->where('payment_invoices.PAYMENT_ID', '=', $PAYMENT_ID)
            ->get();

        return $result;
    }

    public function PaymenIsOver(int $INVOICE_ID, float $NEW_APPLIED): bool
    {
        $data = Invoice::where('ID', $INVOICE_ID)->first();

        if ($data) {
            $ORG_AMOUNT  = $data->AMOUNT ?? 0;
            $AMOUNT_PAID = $NEW_APPLIED + (float) PaymentInvoices::where('INVOICE_ID', '=', $INVOICE_ID)->sum("AMOUNT_APPLIED");

            $BALANCE = $ORG_AMOUNT - $AMOUNT_PAID;

            if ($BALANCE < 0) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function getPosted(int $PAYMENT_ID, string $DATE, $LOCATION_ID): bool
    {

        $payment           = $this->object_type_payment;
        $paymentInvoicesId = $this->object_type_payment_invoices;
        $JOURNAL_NO        = (int) $this->accountJournalServices->getRecord($payment, $PAYMENT_ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($payment, $PAYMENT_ID) + 1;
        }

        $paymentData = $this->PaymentJournal($PAYMENT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentData,
            $LOCATION_ID,
            $payment,
            $DATE,
            "UF"
        );

        $paymentDataR = $this->PaymentJournalRemaining($PAYMENT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentDataR,
            $LOCATION_ID,
            $payment,
            $DATE,
            "A/R"
        );

        $paymentInvoiceData = $this->PaymentInvoicejournal($PAYMENT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentInvoiceData,
            $LOCATION_ID,
            $paymentInvoicesId,
            $DATE,
            "A/R"
        );

        $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
        $debit_sum  = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum) {
            $this->StatusUpdate($PAYMENT_ID, 15);
            return true;
        }

        return false;
    }

    public function getPaymentbyPaymentPeriod(int $PAYMENT_PERIOD_ID)
    {

        $result = Payment::query()
            ->select([
                'payment.ID',
                'payment_invoices.INVOICE_ID',
                'philhealth_prof_fee.BILL_ID',
            ])
            ->join('payment_invoices', 'payment_invoices.PAYMENT_ID', '=', 'payment.ID')
            ->join('philhealth', 'philhealth.INVOICE_ID', '=', 'payment_invoices.INVOICE_ID')
            ->leftJoin('philhealth_prof_fee', 'philhealth_prof_fee.PHIC_ID', '=', 'philhealth.ID')
            ->where('payment.PAYMENT_PERIOD_ID', '=', $PAYMENT_PERIOD_ID)
            ->get();

        return $result;
    }
    // public function getListInvoicePaymentTaxBillPhic(int $PAYMENT_PERIOD_ID): object
    // {

    //     $result = Payment::query()
    //         ->select([
    //             'payment_invoices.INVOICE_ID',
    //             'philhealth.P1_TOTAL as INVOICE_AMOUNT',
    //             'philhealth.DATE_ADMITTED',
    //             'philhealth.DATE_DISCHARGED',
    //             'philhealth.AR_NO',
    //             'philhealth.AR_DATE',
    //             'philhealth.ID as PHILHEALTH_ID',
    //             'payment_invoices.PAYMENT_ID',
    //             'payment_invoices.AMOUNT_APPLIED as PAYMENT_AMOUNT',
    //             'philhealth_prof_fee.BILL_ID',
    //             'philhealth_prof_fee.FIRST_CASE as BILL_AMOUNT',
    //             'tax_credit_invoices.TAX_CREDIT_ID',
    //             'tax_credit_invoices.AMOUNT_WITHHELD as TAX_AMOUNT',
    //             'p.NAME as PATIENT_NAME',
    //             'd.NAME as DOCTOR_NAME',
    //             DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as HEMO_TOTAL'),
    //             DB::raw(" (select  GROUP_CONCAT(hemodialysis.DATE ORDER BY hemodialysis.DATE ASC SEPARATOR ', ') from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as CONFINE_PERIOD "),
    //         ])
    //         ->join('payment_invoices', 'payment_invoices.PAYMENT_ID', '=', 'payment.ID')
    //         ->join('philhealth', 'philhealth.INVOICE_ID', '=', 'payment_invoices.INVOICE_ID')
    //         ->join('contact as p', 'p.ID', '=', 'philhealth.CONTACT_ID')
    //         ->leftJoin('philhealth_prof_fee', 'philhealth_prof_fee.PHIC_ID', '=', 'philhealth.ID')
    //         ->join('contact as d', 'd.ID', '=', 'philhealth_prof_fee.CONTACT_ID')
    //         ->leftJoin('tax_credit_invoices', 'tax_credit_invoices.INVOICE_ID', '=', 'payment_invoices.INVOICE_ID')
    //         ->where('payment.PAYMENT_PERIOD_ID', '=', $PAYMENT_PERIOD_ID)
    //         ->get();

    //     return $result;
    // }
    public function getListInvoicePaymentTaxBillPhic(int $PAYMENT_PERIOD_ID): object
    {

        $result = Payment::query()
            ->select([
                'payment.ID as PAYMENT_ID',
                'philhealth.P1_TOTAL as INVOICE_AMOUNT',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                'philhealth.AR_NO',
                'philhealth.AR_DATE',
                'philhealth.ID as PHILHEALTH_ID',
                DB::raw(' (SELECT sum(Amount_Applied) FROM payment_invoices WHERE payment_invoices.PAYMENT_ID = payment.ID) as PAYMENT_AMOUNT'),
                'philhealth_prof_fee.BILL_ID',
                'philhealth_prof_fee.FIRST_CASE as BILL_AMOUNT',
                DB::raw(' (SELECT sum(AMOUNT_WITHHELD) FROM tax_credit_invoices inner join payment_invoices on tax_credit_invoices.INVOICE_ID = payment_invoices.INVOICE_ID WHERE payment_invoices.PAYMENT_ID = payment.ID) as TAX_AMOUNT'),
                'p.NAME as PATIENT_NAME',
                'd.NAME as DOCTOR_NAME',
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as HEMO_TOTAL'),
                DB::raw(" (select  GROUP_CONCAT(hemodialysis.DATE ORDER BY hemodialysis.DATE ASC SEPARATOR ', ') from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as CONFINE_PERIOD "),
            ])
            ->join('philhealth', 'philhealth.PAYMENT_ID', '=', 'payment.ID')
            ->join('contact as p', 'p.ID', '=', 'philhealth.CONTACT_ID')
            ->leftJoin('philhealth_prof_fee', 'philhealth_prof_fee.PHIC_ID', '=', 'philhealth.ID')
            ->join('contact as d', 'd.ID', '=', 'philhealth_prof_fee.CONTACT_ID')
            ->where('payment.PAYMENT_PERIOD_ID', '=', $PAYMENT_PERIOD_ID)
            ->get();

        return $result;
    }

    public function getUpdateDateOnly(int $ID, string $DATE)
    {
        Payment::where('ID', '=', $ID)
            ->update(['DATE' => $DATE]);

        DB::table('patient_payment as pp')
            ->join('philhealth as ph', 'ph.ID', '=', 'pp.PHILHEALTH_ID')
            ->join('payment_invoices as pn', 'pn.INVOICE_ID', '=', 'ph.INVOICE_ID')
            ->join('payment as p', 'p.ID', '=', 'pn.PAYMENT_ID')
            ->where('pp.PAYMENT_METHOD_ID', '=', 91)
            ->where('p.ID', '=', $ID)
            ->update([
                'pp.DATE' => $DATE,
            ]);
    }
    public function getPaymentIdViaInvoiceID(int $INVOICE_ID): int
    {
        $data = PaymentInvoices::where('INVOICE_ID', '=', $INVOICE_ID)->first();
        if ($data) {
            return (int) $data->PAYMENT_ID;
        }

        return 0;
    }
    public function updateXero(int $ID, bool $IS_XERO, float $AMOUNT): void
    {
        Payment::where('ID', $ID)
            ->update([
                'IS_XERO' => $IS_XERO,
                'AMOUNT'  => $AMOUNT,
            ]);
    }

    public function listViaContact(int $CONTACT_ID)
    {
        $result = Payment::query()
            ->select([
                'payment.ID',
                'payment.CODE',
                'payment.DATE',
                'payment.AMOUNT',
                'payment.AMOUNT_APPLIED',
                'payment.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', 'l.ID', '=', 'payment.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'payment.STATUS')
            ->where('payment.CUSTOMER_ID', '=', $CONTACT_ID)
            ->orderBy('payment.DATE', 'desc')
            ->get();

        return $result;
    }
}
