<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\TaxCredit;
use App\Models\TaxCreditInvoices;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TaxCreditServices
{
    public int $object_type_tax_credit          = 72;
    public int $object_type_tax_credit_invoices = 73;
    public int $document_type_id                = 20;

    private $object;
    private $dateServices;
    private $systemSettingServices;
    private $invoiceServices;
    private $accountJournalServices;
    private $usersLogServices;
    public function __construct(
        ObjectServices $objectServices,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        InvoiceServices $invoiceServices,
        AccountJournalServices $accountJournalServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                 = $objectServices;
        $this->dateServices           = $dateServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->invoiceServices        = $invoiceServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->usersLogServices       = $usersLogServices;
    }
    public function Get(int $ID)
    {
        return TaxCredit::where('ID', '=', $ID)->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $CUSTOMER_ID,
        int $EWT_ID,
        float $EWT_RATE,
        int $EWT_ACCOUNT_ID,
        int $LOCATION_ID,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID
    ): int {

        $ID          = (int) $this->object->ObjectNextID('TAX_CREDIT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('TAX_CREDIT');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        TaxCredit::create([
            'ID'                     => $ID,
            'RECORDED_ON'            => $this->dateServices->Now(),
            'CODE'                   => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                   => $DATE,
            'CUSTOMER_ID'            => $CUSTOMER_ID,
            'EWT_ID'                 => $EWT_ID,
            'EWT_RATE'               => $EWT_RATE,
            'EWT_ACCOUNT_ID'         => $EWT_ACCOUNT_ID > 0 ? $EWT_ACCOUNT_ID : null,
            'LOCATION_ID'            => $LOCATION_ID,
            'AMOUNT'                 => 0,
            'NOTES'                  => $NOTES,
            'STATUS'                 => 0,
            'STATUS_DATE'            => $this->dateServices->NowDate(),
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::TAX_CREDIT, $ID);
        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        int $EWT_ID,
        float $EWT_RATE,
        int $EWT_ACCOUNT_ID,
        string $NOTES,
        float $AMOUNT,
        int $ACCOUNTS_RECEIVABLE_ID
    ) {

        TaxCredit::where('ID', '=', $ID)
            ->update([
                'CODE'                   => $CODE,
                'EWT_ID'                 => $EWT_ID,
                'EWT_RATE'               => $EWT_RATE,
                'EWT_ACCOUNT_ID'         => $EWT_ACCOUNT_ID > 0 ? $EWT_ACCOUNT_ID : null,
                'NOTES'                  => $NOTES,
                'AMOUNT'                 => $AMOUNT,
                'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::TAX_CREDIT, $ID);
    }

    public function Delete(int $ID)
    {
        TaxCreditInvoices::where('TAX_CREDIT_ID', '=', $ID)->delete();
        TaxCredit::where('ID', '=', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::TAX_CREDIT, $ID);
    }
    public function setTotal(int $TAX_CREDIT_ID, float $AMOUNT)
    {
        TaxCredit::where('ID', $TAX_CREDIT_ID)
            ->update(
                [
                    'AMOUNT' => $AMOUNT,
                ]
            );
    }
    public function Search($search, int $LOCATION_ID, int $perPage): LengthAwarePaginator
    {

        $result = TaxCredit::query()
            ->select([
                'tax_credit.ID',
                'tax_credit.CODE',
                'tax_credit.DATE',
                'tax_credit.AMOUNT',
                'tax_credit.NOTES',
                'tax_credit.EWT_RATE',
                'c.PRINT_NAME_AS as NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'tax_credit.STATUS as STATUS_ID',

            ])
            ->join('contact as c', 'c.ID', '=', 'tax_credit.CUSTOMER_ID')
            ->join('account as a', 'a.ID', '=', 'tax_credit.EWT_ACCOUNT_ID')
            ->join('document_status_map as s', 's.ID', '=', 'tax_credit.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'tax_credit.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('tax as t', 't.ID', '=', 'tax_credit.EWT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('tax_credit.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('tax_credit.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('tax_credit.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tax_credit.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function StatusUpdate(int $ID, int $STATUS): void
    {
        TaxCredit::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::TAX_CREDIT, $ID);
    }
    public function StoreInvoice(int $TAX_CREDIT_ID, int $INVOICE_ID, float $AMOUNT_WITHHELD, int $ACCOUNTS_RECEIVABLE_ID): int
    {

        $ID = (int) $this->object->ObjectNextID('TAX_CREDIT_INVOICES');
        TaxCreditInvoices::create(
            [
                'ID'                     => $ID,
                'TAX_CREDIT_ID'          => $TAX_CREDIT_ID,
                'INVOICE_ID'             => $INVOICE_ID,
                'AMOUNT_WITHHELD'        => $AMOUNT_WITHHELD,
                'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
            ]
        );
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::TAX_CREDIT_INVOICES, $TAX_CREDIT_ID);
        return $ID;
    }
    public function UpdateInvoice(int $ID, int $TAX_CREDIT_ID, int $INVOICE_ID, float $AMOUNT_WITHHELD)
    {

        TaxCreditInvoices::where('ID', '=', $ID)
            ->where('TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->update(
                [
                    'AMOUNT_WITHHELD' => $AMOUNT_WITHHELD,
                ]
            );

             $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::TAX_CREDIT_INVOICES, $TAX_CREDIT_ID);
    }
    public function TaxCreditInvoiceExists(int $TAX_CREDIT_ID, int $INVOICE_ID): bool
    {
        return TaxCreditInvoices::where('TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->exists();
    }
    public function GetTaxCreditInvoiceExists(int $ID, int $TAX_CREDIT_ID, int $INVOICE_ID)
    {
        $result = TaxCreditInvoices::where('ID', '=', $ID)
            ->where('TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->first();

        if ($result) {
            return $result;
        }

        return [];
    }

    public function DeleteInvoice(int $ID, int $TAX_CREDIT_ID)
    {
        TaxCreditInvoices::where('ID', '=', $ID)->delete();

         $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::TAX_CREDIT_INVOICES, $TAX_CREDIT_ID);
    }
    public function GetTaxCreditInvoice(int $ID)
    {

        return TaxCreditInvoices::where('ID', '=', $ID)->first();
    }
    public function GetInvoiceList(int $TAX_CREDIT_ID)
    {

        $result = TaxCreditInvoices::query()
            ->select([
                'tax_credit_invoices.ID',
                'tax_credit_invoices.INVOICE_ID',
                'tax_credit_invoices.AMOUNT_WITHHELD',
                'tax_credit_invoices.ACCOUNTS_RECEIVABLE_ID',
                'i.CODE',
                'i.DATE',
                'i.AMOUNT as ORG_AMOUNT',
                'i.TAXABLE_AMOUNT',
                'i.BALANCE_DUE',

            ])
            ->join('invoice as i', 'i.ID', '=', 'tax_credit_invoices.INVOICE_ID')
            ->where('tax_credit_invoices.TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->get();

        return $result;
    }

    public function UpdateAMOUNT_WITHHELD(int $TAX_CREDIT_ID, float $EWT_RATE): float
    {
        $TOTAL  = 0;
        $result = TaxCreditInvoices::query()
            ->select([
                'tax_credit_invoices.INVOICE_ID',
                'tax_credit_invoices.ID',
                'i.AMOUNT',
            ])
            ->join('invoice as i', 'i.ID', '=', 'tax_credit_invoices.INVOICE_ID')
            ->where('TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->get();

        foreach ($result as $row) {
            $INVOICE_AMOUNT  = (float) $row->AMOUNT ?? 0;
            $AMT_WITHHELD    = (float) $INVOICE_AMOUNT * ($EWT_RATE / 100);
            $TOTAL          += $AMT_WITHHELD;
            $this->UpdateInvoice($row->ID, $TAX_CREDIT_ID, $row->INVOICE_ID, $AMT_WITHHELD);
            $this->invoiceServices->updateInvoiceBalance($row->INVOICE_ID);
        }

        return $TOTAL;
    }

    public function getTotal(int $TAX_CREDIT_ID): float
    {
        $TOTAL  = 0;
        $result = TaxCreditInvoices::query()
            ->select([
                'tax_credit_invoices.AMOUNT_WITHHELD',
            ])
            ->where('TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->get();

        foreach ($result as $row) {
            $AMOUNT_WITHHELD  = (float) $row->AMOUNT_WITHHELD ?? 0;
            $TOTAL           += $AMOUNT_WITHHELD;
        }

        return $TOTAL;
    }

    public function TaxCreditJournal(int $TAX_CREDIT_ID)
    {
        $result = TaxCredit::query()
            ->select([
                'ID',
                'EWT_ACCOUNT_ID as ACCOUNT_ID',
                'CUSTOMER_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $TAX_CREDIT_ID)
            ->get();

        return $result;
    }
    public function TaxCreditJournalRemaining(int $TAX_CREDIT_ID)
    {
        $result = TaxCredit::query()
            ->select([
                'tax_credit.ID',
                'tax_credit.ACCOUNTS_RECEIVABLE_ID as ACCOUNT_ID',
                'tax_credit.CUSTOMER_ID as SUBSIDIARY_ID',
                DB::raw("(tax_credit.AMOUNT - (select IFNULL(sum(tax_credit_invoices.AMOUNT_WITHHELD),0)  from tax_credit_invoices where tax_credit_invoices.TAX_CREDIT_ID = tax_credit.ID limit 1)) as AMOUNT"),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('tax_credit.ID', '=', $TAX_CREDIT_ID)
            ->get();

        return $result;
    }
    public function TaxCreditInvoicejournal(int $TAX_CREDIT_ID)
    {
        $result = TaxCreditInvoices::query()
            ->select([
                'tax_credit_invoices.ID',
                'tax_credit_invoices.ACCOUNTS_RECEIVABLE_ID as ACCOUNT_ID',
                'tax_credit_invoices.INVOICE_ID as SUBSIDIARY_ID',
                'tax_credit_invoices.AMOUNT_WITHHELD as AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])->join('tax_credit', 'tax_credit.ID', '=', 'tax_credit_invoices.TAX_CREDIT_ID')
            ->where('tax_credit_invoices.TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->get();

        return $result;
    }
    public function InvoiceTaxCreditList(int $INVOICE_ID, int $CUSTOMER_ID)
    {
        return TaxCredit::query()
            ->select([
                'tax_credit.ID',
                'tax_credit.CODE',
                'tax_credit.DATE',
                'tax_credit.AMOUNT',
                'tax.NAME as TAX_TYPE',
                'tax_credit_invoices.AMOUNT_WITHHELD',
                'a.NAME as TAX_ACCOUNT',
                'tax_credit.NOTES',
            ])
            ->join('tax', 'tax.ID', '=', 'tax_credit.EWT_ID')
            ->join('tax_credit_invoices', 'tax_credit_invoices.TAX_CREDIT_ID', '=', 'tax_credit.ID')
            ->leftJoin('account as a', 'a.ID', '=', 'tax_credit.EWT_ACCOUNT_ID')
            ->where('tax_credit_invoices.INVOICE_ID', '=', $INVOICE_ID)
            ->where('tax_credit.CUSTOMER_ID', '=', $CUSTOMER_ID)
            ->get();
    }
    public function getTotalPay(int $INVOICE_ID, int $EXCEPT_TAX_CREDIT_ID): float
    {
        $data = TaxCreditInvoices::query()
            ->selectRaw('ifnull(sum(AMOUNT_WITHHELD),0) as total')
            ->where('INVOICE_ID', $INVOICE_ID)
            ->where('TAX_CREDIT_ID', '<>', $EXCEPT_TAX_CREDIT_ID)
            ->first();

        if ($data) {
            return $data->total;
        }

        return 0;
    }

    public function getPosted(int $TAX_CREDIT_ID, string $DATE, int $LOCATION_ID): bool
    {
        $taxCredit         = $this->object_type_tax_credit;
        $taxCreditInvoices = $this->object_type_tax_credit_invoices;
        $JOURNAL_NO        = (int) $this->accountJournalServices->getRecord($taxCredit, $TAX_CREDIT_ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($taxCredit, $TAX_CREDIT_ID) + 1;
        }

        $paymentData = $this->TaxCreditJournal($TAX_CREDIT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentData,
            $LOCATION_ID,
            $taxCredit,
            $DATE,
            "TAX"
        );

        $paymentDataR = $this->TaxCreditJournalRemaining($TAX_CREDIT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentDataR,
            $LOCATION_ID,
            $taxCredit,
            $DATE,
            "A/R"
        );

        $paymentInvoiceData = $this->TaxCreditInvoicejournal($TAX_CREDIT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentInvoiceData,
            $LOCATION_ID,
            $taxCreditInvoices,
            $DATE,
            "A/R"
        );

        $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
        $debit_sum  = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum) {
            $this->StatusUpdate($TAX_CREDIT_ID, 15);
            return true;
        }

        return false;
    }

    public function updateDateOnly(int $INVOICE_ID, $NEW_DATE): int
    {
        $data = TaxCreditInvoices::where('INVOICE_ID', '=', $INVOICE_ID)->first();
        if ($data) {
            TaxCredit::where('ID', '=', $data->TAX_CREDIT_ID)->update(['DATE' => $NEW_DATE]);
            return (int) $data->TAX_CREDIT_ID;
        }

        return 0;

    }

    public function getTaxCreditIdViaInvoiceID(int $INVOICE_ID): int
    {
        $data = TaxCreditInvoices::where('INVOICE_ID', '=', $INVOICE_ID)->first();
        if ($data) {
            return (int) $data->TAX_CREDIT_ID;
        }
        return 0;
    }

    public function listViaContact(int $CONTACT_ID)
    {

        $result = TaxCredit::query()
            ->select([
                'tax_credit.ID',
                'tax_credit.CODE',
                'tax_credit.DATE',
                'tax_credit.AMOUNT',
                'tax_credit.NOTES',
                'tax_credit.EWT_RATE',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'tax_credit.STATUS as STATUS_ID',
            ])
            ->join('document_status_map as s', 's.ID', '=', 'tax_credit.STATUS')
            ->join('location as l', 'l.ID', '=', 'tax_credit.LOCATION_ID')
            ->where('tax_credit.CUSTOMER_ID', '=', $CONTACT_ID)
            ->orderBy('tax_credit.DATE', 'desc')
            ->get();

        return $result;
    }

    public function GetListViaPayments(int $PAYMENT_ID)
    {
        // SELECT tax_credit_invoices.`TAX_CREDIT_ID` FROM tax_credit_invoices INNER JOIN payment_invoices ON tax_credit_invoices.INVOICE_ID = payment_invoices.INVOICE_ID WHERE payment_invoices.PAYMENT_ID = 32 GROUP BY  tax_credit_invoices.`TAX_CREDIT_ID`

        $result = TaxCreditInvoices::query()
            ->select(["TAX_CREDIT_ID"])
            ->join('payment_invoices as p', 'p.INVOICE_ID', '=', 'tax_credit_invoices.INVOICE_ID')
            ->where('p.PAYMENT_ID', '=', $PAYMENT_ID)
            ->groupBy('TAX_CREDIT_ID')
            ->get();

        return $result;
    }

}
