<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\CreditMemoInvoices;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\PaymentInvoices;
use App\Models\PhilHealth;
use App\Models\SalesOrderItems;
use App\Models\Tax;
use App\Models\TaxCreditInvoices;
use Illuminate\Support\Facades\DB;

class InvoiceServices
{

    public int $object_type_invoice      = 23;
    public int $object_type_invoice_item = 24;
    public int $document_type_id         = 10;
    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;

    private $usersLogServices;

    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        UsersLogServices $usersLogServices

    ) {
        $this->object                = $objectService;
        $this->compute               = $computeServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function getBalance(int $INVOICE_ID): float
    {
        return (float) Invoice::where('ID', '=', $INVOICE_ID)->first()->BALANCE_DUE;
    }
    public function getInvoiceListViaPayment(int $CUSTOMER_ID, int $LOCATION_ID, int $PAYMENT_ID)
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.DATE',
                'invoice.CODE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
            ])
            ->whereNotExists(function ($query) use (&$PAYMENT_ID) {
                $query->select(DB::raw(1))
                    ->from('payment_invoices as p')
                    ->whereRaw('p.INVOICE_ID = invoice.ID')
                    ->where('p.PAYMENT_ID', '=', $PAYMENT_ID);
            })
            ->where('invoice.CUSTOMER_ID', $CUSTOMER_ID)
            ->where('invoice.LOCATION_ID', $LOCATION_ID)
            ->where('invoice.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }

    public function getInvoiceListViaTaxCredit(int $CUSTOMER_ID, int $LOCATION_ID, int $TAX_CREDIT_ID)
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.DATE',
                'invoice.CODE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',

            ])
            ->whereNotExists(function ($query) use (&$TAX_CREDIT_ID) {
                $query->select(DB::raw(1))
                    ->from('tax_credit_invoices as p')
                    ->whereRaw('p.INVOICE_ID = invoice.ID')
                    ->where('p.TAX_CREDIT_ID', '=', $TAX_CREDIT_ID);
            })
            ->where('invoice.CUSTOMER_ID', $CUSTOMER_ID)
            ->where('invoice.LOCATION_ID', $LOCATION_ID)
            ->where('invoice.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }
    public function getInvoiceListViaCreditMemo(int $CUSTOMER_ID, int $LOCATION_ID, int $CREDIT_MEMO_ID)
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.DATE',
                'invoice.CODE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
            ])
            ->whereNotExists(function ($query) use (&$CREDIT_MEMO_ID) {
                $query->select(DB::raw(1))
                    ->from('credit_memo_invoices as p')
                    ->whereRaw('p.INVOICE_ID = invoice.ID')
                    ->where('p.CREDIT_MEMO_ID', '=', $CREDIT_MEMO_ID);
            })
            ->where('invoice.CUSTOMER_ID', $CUSTOMER_ID)
            ->where('invoice.LOCATION_ID', $LOCATION_ID)
            ->where('invoice.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }
    public function get(int $ID)
    {
        $data = Invoice::where('ID', '=', $ID)->first();
        if ($data) {
            return $data;
        }

        return null;
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        int $CLASS_ID,
        int $SALES_REP_ID,
        string $PO_NUMBER,
        string $SHIP_TO,
        int $SHIP_VIA_ID,
        $SHIP_DATE,
        int $PAYMENT_TERMS_ID,
        $DUE_DATE,
        $DISCOUNT_DATE,
        float $DISCOUNT_PCT,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $STATUS,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        int $OUTPUT_TAX_VAT_METHOD,
        int $OUTPUT_TAX_ACCOUNT_ID,
        int $TRANSACTION_REF_ID = 0
    ): int {
        $ID = (int) $this->object->ObjectNextID('INVOICE');

        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('INVOICE');

        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Invoice::create([
            'ID'                     => $ID,
            'RECORDED_ON'            => $this->dateServices->Now(),
            'CODE'                   => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                   => $DATE,
            'CUSTOMER_ID'            => $CUSTOMER_ID,
            'LOCATION_ID'            => $LOCATION_ID,
            'CLASS_ID'               => $CLASS_ID > 0 ? $CLASS_ID : null,
            'SALES_REP_ID'           => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
            'PO_NUMBER'              => $PO_NUMBER ?? '',
            'SHIP_TO'                => $SHIP_TO ? $SHIP_TO : null,
            'SHIP_VIA_ID'            => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
            'SHIP_DATE'              => $SHIP_DATE ?? null,
            'PAYMENT_TERMS_ID'       => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
            'DUE_DATE'               => $DUE_DATE ?? null,
            'DISCOUNT_DATE'          => $this->dateServices->isValidDateFormat($DISCOUNT_DATE) ? $DISCOUNT_DATE : null,
            'DISCOUNT_PCT'           => $DISCOUNT_PCT ?? null,
            'NOTES'                  => $NOTES ?? null,
            'AMOUNT'                 => 0,
            'BALANCE_DUE'            => 0,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
            'STATUS'                 => $STATUS,
            'STATUS_DATE'            => $this->dateServices->NowDate(),
            'OUTPUT_TAX_ID'          => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
            'OUTPUT_TAX_RATE'        => $OUTPUT_TAX_RATE,
            'OUTPUT_TAX_VAT_METHOD'  => $OUTPUT_TAX_VAT_METHOD,
            'OUTPUT_TAX_ACCOUNT_ID'  => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,
            'TRANSACTION_REF_ID'     => $TRANSACTION_REF_ID,

        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::INVOICE, $ID);

        return $ID;
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        Invoice::where('ID', $ID)->update([
            'STATUS'      => $STATUS,
            'STATUS_DATE' => $this->dateServices->NowDate(),
        ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::INVOICE, $ID);
    }
    public function UpdateParameter(int $ID, $param = [])
    {
        Invoice::where('ID', '=', $ID)->update($param);
    }
    public function Update(int $ID, string $CODE, string $DATE, int $CUSTOMER_ID, int $LOCATION_ID, int $CLASS_ID, int $SALES_REP_ID, string $PO_NUMBER, string $SHIP_TO, int $SHIP_VIA_ID, $SHIP_DATE, int $PAYMENT_TERMS_ID, $DUE_DATE, $DISCOUNT_DATE, float $DISCOUNT_PCT, string $NOTES, int $ACCOUNTS_RECEIVABLE_ID, int $STATUS, int $OUTPUT_TAX_ID, float $OUTPUT_TAX_RATE, int $OUTPUT_TAX_VAT_METHOD, int $OUTPUT_TAX_ACCOUNT_ID): void
    {

        Invoice::where('ID', '=', $ID)
            ->update([
                'CODE'                   => $CODE,
                'DATE'                   => $DATE,
                'CUSTOMER_ID'            => $CUSTOMER_ID,
                'LOCATION_ID'            => $LOCATION_ID,
                'CLASS_ID'               => $CLASS_ID > 0 ? $CLASS_ID : null,
                'SALES_REP_ID'           => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
                'PO_NUMBER'              => $PO_NUMBER ?? '',
                'SHIP_TO'                => $SHIP_TO ? $SHIP_TO : null,
                'SHIP_VIA_ID'            => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
                'SHIP_DATE'              => $SHIP_DATE ?? null,
                'PAYMENT_TERMS_ID'       => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
                'DUE_DATE'               => $DUE_DATE ?? null,
                'DISCOUNT_DATE'          => $DISCOUNT_DATE ?? null,
                'DISCOUNT_PCT'           => $DISCOUNT_PCT ?? null,
                'NOTES'                  => $NOTES ?? null,
                'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
                'OUTPUT_TAX_ID'          => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
                'OUTPUT_TAX_RATE'        => $OUTPUT_TAX_RATE,
                'OUTPUT_TAX_VAT_METHOD'  => $OUTPUT_TAX_VAT_METHOD,
                'OUTPUT_TAX_ACCOUNT_ID'  => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::INVOICE, $ID);
    }
    public function Delete(int $ID): void
    {
        InvoiceItems::where('INVOICE_ID', $ID)->delete();
        Invoice::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::INVOICE, $ID);
    }
    public function Search($search, int $locationId, int $perPage)
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.CODE',
                'invoice.DATE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
                'invoice.OUTPUT_TAX_RATE',
                'invoice.NOTES',
                'invoice.PO_NUMBER',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
                'invoice.STATUS as STATUS_ID',
            ])
            ->join('contact as c', 'c.ID', '=', 'invoice.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'invoice.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'invoice.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'invoice.OUTPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('invoice.CODE', 'like', '%' . $search . '%')
                        ->orWhere('invoice.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('invoice.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('invoice.PO_NUMBER', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('invoice.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    private function getLine($Id): int
    {
        return (int) InvoiceItems::where('INVOICE_ID', $Id)->max('LINE_NO');
    }
    public function ItemStore(
        int $INVOICE_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $COGS_ACCOUNT_ID,
        int $ASSET_ACCOUNT_ID,
        int $INCOME_ACCOUNT_ID,
        int $REF_LINE_ID,
        int $BATCH_ID,
        int $GROUP_LINE_ID,
        bool $PRINT_IN_FORMS,
        bool $DEPOSITED,
        int $PRICE_LEVEL_ID,
    ): int {

        $LINE_NO = $this->getLine($INVOICE_ID) + 1;
        $ID      = $this->object->ObjectNextID('INVOICE_ITEMS');

        InvoiceItems::create([
            'ID'                 => $ID,
            'INVOICE_ID'         => $INVOICE_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
            'DESCRIPTION'        => null,
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'               => $RATE,
            'RATE_TYPE'          => $RATE_TYPE,
            'AMOUNT'             => $AMOUNT,
            'TAXABLE'            => $TAXABLE,
            'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'         => $TAX_AMOUNT,
            'COGS_ACCOUNT_ID'    => $COGS_ACCOUNT_ID > 0 ? $COGS_ACCOUNT_ID : null,
            'ASSET_ACCOUNT_ID'   => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'INCOME_ACCOUNT_ID'  => $INCOME_ACCOUNT_ID > 0 ? $INCOME_ACCOUNT_ID : null,
            'REF_LINE_ID'        => $REF_LINE_ID > 0 ? $REF_LINE_ID : null,
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'GROUP_LINE_ID'      => $GROUP_LINE_ID > 0,
            'PRINT_IN_FORMS'     => $PRINT_IN_FORMS,
            'DEPOSITED'          => $DEPOSITED,
            'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::INVOICE_ITEMS, $INVOICE_ID);
        return $ID;
    }
    public function ItemGet(int $ID, int $INVOICE_ID, int $ITEM_ID = 0)
    {
        return InvoiceItems::where('ID', '=', $ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->when($ITEM_ID > 0, function ($query) use (&$ITEM_ID) {
                $query->where('ITEM_ID', '=', $ITEM_ID);
            })
            ->first();
    }
    public function ItemUpdate(
        int $ID,
        int $INVOICE_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE = 0,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $BATCH_ID,
        int $PRICE_LEVEL_ID,
        int $INCOME_ACCOUNT_ID
    ) {
        $data = $this->ItemGet($ID, $INVOICE_ID, $ITEM_ID);

        if ($data) {
            $data->update([
                'QUANTITY'           => $QUANTITY,
                'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'RATE'               => $RATE,
                'RATE_TYPE'          => $RATE_TYPE,
                'AMOUNT'             => $AMOUNT,
                'TAXABLE'            => $TAXABLE,
                'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
                'TAX_AMOUNT'         => $TAX_AMOUNT,
                'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
                'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
                'INCOME_ACCOUNT_ID'  => $INCOME_ACCOUNT_ID > 0 ? $INCOME_ACCOUNT_ID : null,
            ]);

            if ($data->REF_LINE_ID) {
                SalesOrderItems::where('ID', $data->REF_LINE_ID)
                    ->update([
                        'INVOICED_QTY' => $QUANTITY,
                    ]);
            }

            $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::INVOICE_ITEMS, $INVOICE_ID);
        }
    }
    public function ItemDelete(int $ID, int $INVOICE_ID)
    {
        InvoiceItems::where('ID', $ID)->where('INVOICE_ID', $INVOICE_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::INVOICE_ITEMS, $INVOICE_ID);
    }
    public function ItemView(int $INVOICE_ID)
    {
        return InvoiceItems::query()
            ->select([
                'invoice_items.ID',
                'invoice_items.ITEM_ID',
                'invoice_items.INVOICE_ID',
                'invoice_items.QUANTITY',
                'invoice_items.UNIT_ID',
                'invoice_items.RATE',
                'invoice_items.AMOUNT',
                'invoice_items.TAXABLE',
                'invoice_items.TAXABLE_AMOUNT',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                'c.DESCRIPTION as CLASS_DESCRIPTION',
                'invoice_items.INCOME_ACCOUNT_ID',
                'a.NAME as ACCOUNT_NAME',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'invoice_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'invoice_items.UNIT_ID')
            ->leftJoin('item_sub_class as sl', 'sl.ID', '=', 'i.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 'sl.CLASS_ID')
            ->leftJoin('account as a', 'a.ID', '=', 'invoice_items.INCOME_ACCOUNT_ID')
            ->where('invoice_items.INVOICE_ID', $INVOICE_ID)
            ->orderBy('invoice_items.LINE_NO', 'asc')
            ->get();
    }
    public function ReComputed(int $ID): array
    {
        $invoice = Invoice::where('ID', $ID)->first();

        if ($invoice) {
            $TAX_ID = (int) $invoice->OUTPUT_TAX_ID;

            $itemResult = InvoiceItems::query()
                ->select(
                    [
                        'invoice_items.AMOUNT',
                        'invoice_items.TAX_AMOUNT',
                        'invoice_items.TAXABLE_AMOUNT',
                        'invoice_items.TAXABLE',
                        'item.TYPE',
                    ]
                )
                ->join('item', 'item.ID', '=', 'invoice_items.ITEM_ID')
                ->where('invoice_items.INVOICE_ID', $ID)
                ->whereIn('item.TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
                ->orderBy('invoice_items.LINE_NO', 'asc')
                ->get();

            $paymentApplied = (float) $this->GetPaymentApplied($ID);
            $creditApplied  = (float) $this->GetCreditApplied($ID);
            $taxCredit      = (float) $this->GetTaxCredit($ID);

            $totalPay = (float) $paymentApplied + $creditApplied + $taxCredit;

            $data = $this->compute->taxCompute($itemResult, $TAX_ID);
            foreach ($data as $list) {
                $originalAmount = (float) $list['AMOUNT'];
                $balance        = (float) $originalAmount - $totalPay;
                Invoice::where('ID', '=', $ID)
                    ->update([
                        'AMOUNT'            => $originalAmount,
                        'BALANCE_DUE'       => $balance,
                        'OUTPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                        'TAXABLE_AMOUNT'    => $list['TAXABLE_AMOUNT'],
                        'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT'],
                    ]);

                $result = [
                    [
                        'AMOUNT'            => $originalAmount,
                        'BALANCE_DUE'       => $balance,
                        'TAX_AMOUNT'        => $list['TAX_AMOUNT'],
                        'TAXABLE_AMOUNT'    => $list['TAXABLE_AMOUNT'],
                        'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT'],
                    ],
                ];

                return $result;
            }
        }
        return [];
    }

    public function GetPaymentApplied(int $INVOICE_ID): float
    {
        $paymentSum = PaymentInvoices::query()
            ->select(DB::raw('IFNULL(SUM(payment_invoices.AMOUNT_APPLIED), 0) AS pay'))
            ->where('payment_invoices.INVOICE_ID', '=', $INVOICE_ID)
            ->first();
        return $paymentSum->pay ?? 0;
    }

    public function GetCreditApplied(int $INVOICE_ID): float
    {
        $paymentSum = CreditMemoInvoices::query()
            ->select(DB::raw('IFNULL(SUM(credit_memo_invoices.AMOUNT_APPLIED), 0) AS pay'))
            ->where('credit_memo_invoices.INVOICE_ID', '=', $INVOICE_ID)
            ->first();

        return $paymentSum->pay ?? 0;
    }

    public function GetTaxCredit(int $INVOICE_ID): float
    {
        $paymentSum = TaxCreditInvoices::query()
            ->select(DB::raw('IFNULL(SUM(tax_credit_invoices.AMOUNT_WITHHELD), 0) AS pay'))
            ->where('tax_credit_invoices.INVOICE_ID', '=', $INVOICE_ID)
            ->first();

        return $paymentSum->pay ?? 0;
    }

    public function updateInvoiceBalance(int $INVOICE_ID)
    {
        $PAYMENT = (float) $this->GetPaymentApplied($INVOICE_ID);
        $CREDIT  = (float) $this->GetCreditApplied($INVOICE_ID);
        $TAX     = (float) $this->GetTaxCredit($INVOICE_ID);
        $PAY     = (float) $PAYMENT + $CREDIT + $TAX;

        $data = Invoice::where('ID', '=', $INVOICE_ID)->first();
        if ($data) {
            $AMOUNT  = (float) $data->AMOUNT;
            $BALANCE = $AMOUNT - $PAY;

            Invoice::where('ID', '=', $INVOICE_ID)
                ->update([
                    'BALANCE_DUE' => $BALANCE,
                ]);
        }
        $phData = PhilHealth::where('INVOICE_ID', '=', $INVOICE_ID);
        if ($phData->exists()) {
            $phData->update([
                'PAYMENT_AMOUNT' => $PAY,
                'STATUS_ID'      => $BALANCE > 0 ? 1 : 11,
            ]);
        }
    }

    public function getUpdateTaxItem(int $INVOICE_ID, int $TAX_ID)
    {
        $items = InvoiceItems::query()
            ->select([
                'invoice_items.ID',
                'invoice_items.AMOUNT',
                'invoice_items.TAXABLE',
            ])
            ->join('item', 'item.ID', '=', 'invoice_items.ITEM_ID')
            ->where('invoice_items.INVOICE_ID', $INVOICE_ID)
            ->where('item.TYPE', 0)
            ->orderBy('invoice_items.LINE_NO', 'asc')
            ->get();

        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;

        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                InvoiceItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }
    }

    public function PaymentHistory($INVOICE_ID)
    {
        $results = DB::table(DB::raw('(
            SELECT \'Payment\' AS `TYPE`, payment_invoices.`ID`, payment_invoices.`PAYMENT_ID` AS MAIN_ID, payment_invoices.`INVOICE_ID`, payment_invoices.`AMOUNT_APPLIED`, payment.`RECORDED_ON`,payment.CODE,payment.DATE
            FROM payment_invoices
            INNER JOIN payment ON payment.`ID` = payment_invoices.`PAYMENT_ID`
            UNION
            SELECT \'Credit Memo\' AS `TYPE`, credit_memo_invoices.`ID`, credit_memo_invoices.`CREDIT_MEMO_ID` AS MAIN_ID, credit_memo_invoices.`INVOICE_ID`, credit_memo_invoices.`AMOUNT_APPLIED`, credit_memo.`RECORDED_ON`,credit_memo.CODE,credit_memo.DATE
            FROM credit_memo_invoices
            INNER JOIN credit_memo ON credit_memo.`ID` = credit_memo_invoices.`CREDIT_MEMO_ID`
        ) AS pay'))
            ->select('pay.TYPE', 'pay.ID', 'pay.MAIN_ID', 'pay.INVOICE_ID', 'pay.AMOUNT_APPLIED', 'pay.RECORDED_ON', 'pay.CODE', 'pay.DATE')
            ->where('pay.INVOICE_ID', '=', $INVOICE_ID)
            ->orderBy('pay.RECORDED_ON')
            ->get();

        return $results;
    }

    public function CountItems(int $INVOICE_ID): int
    {
        return (int) InvoiceItems::where('INVOICE_ID', $INVOICE_ID)->count();
    }

    public function ItemInventory(int $INVOICE_ID)
    {
        $result = InvoiceItems::query()
            ->select([
                'invoice_items.ID',
                'invoice_items.ITEM_ID',
                'invoice_items.RATE',
                'invoice_items.QUANTITY',
                'invoice_items.UNIT_BASE_QUANTITY',
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'invoice_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('invoice_items.INVOICE_ID', $INVOICE_ID)
            ->get();

        return $result;
    }
    public function getInvoiceTaxJournal(int $INVOICE_ID)
    {
        $result = Invoice::query()
            ->select([
                'ID',
                'OUTPUT_TAX_ACCOUNT_ID as ACCOUNT_ID',
                'CUSTOMER_ID as SUBSIDIARY_ID',
                'OUTPUT_TAX_AMOUNT as AMOUNT',
                DB::raw(' 1 as ENTRY_TYPE'),

            ])
            ->where('ID', $INVOICE_ID)
            ->get();

        return $result;
    }
    public function getInvoiceJournal(int $INVOICE_ID)
    {
        $result = Invoice::query()
            ->select([
                'ID',
                'ACCOUNTS_RECEIVABLE_ID as ACCOUNT_ID',
                'CUSTOMER_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 0 as ENTRY_TYPE'),

            ])
            ->where('ID', '=', $INVOICE_ID)
            ->get();

        return $result;
    }
    public function getInvoiceItemJournalIncome(int $INVOICE_ID)
    {
        $result = InvoiceItems::query()
            ->select([
                'ID',
                'INCOME_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('IF(AMOUNT > 0 , 1, 0) as ENTRY_TYPE'),
            ])
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function getInvoiceItemJournalAsset(int $INVOICE_ID)
    {
        $result = InvoiceItems::query()
            ->select([
                'invoice_items.ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('(select  ifnull(sum(p.CUSTOM_COST),0) from price_level_lines as p inner join location as l on l.PRICE_LEVEL_ID = p.PRICE_LEVEL_ID where l.ID = invoice.LOCATION_ID  and p.ITEM_ID = invoice_items.ITEM_ID) * invoice_items.QUANTITY as AMOUNT'),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->join('invoice', 'invoice.ID', '=', 'invoice_items.INVOICE_ID')
            ->where('invoice_items.INVOICE_ID', $INVOICE_ID)
            ->whereNotNull('ASSET_ACCOUNT_ID')
            ->orderBy('invoice_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getInvoiceItemJournalCogs(int $INVOICE_ID)
    {
        $result = InvoiceItems::query()
            ->select([
                'invoice_items.ID',
                'invoice_items.COGS_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('(select  ifnull(sum( p.CUSTOM_COST),0) from price_level_lines as p inner join location as l on l.PRICE_LEVEL_ID = p.PRICE_LEVEL_ID where l.ID = invoice.LOCATION_ID  and p.ITEM_ID = invoice_items.ITEM_ID) * invoice_items.QUANTITY as AMOUNT'),
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->join('invoice', 'invoice.ID', '=', 'invoice_items.INVOICE_ID')
            ->where('invoice_items.INVOICE_ID', $INVOICE_ID)
            ->whereNotNull('invoice_items.COGS_ACCOUNT_ID')
            ->orderBy('invoice_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getActiveList($search, int $LOCATION_ID): object
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.CODE',
                'invoice.DATE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
                'invoice.DUE_DATE',
                'invoice.NOTES',
                'invoice.PO_NUMBER',
                'c.NAME as CUSTOMER_NAME',
                'ph.AR_NO',
                'ph.AR_DATE',
                'ph.CODE as SOA_NO',
                'ph.DATE_ADMITTED',
                'ph.DATE_DISCHARGED',
                'ph.ID as PHILHEALTH_ID',
                'l.NAME as LOCATION_NAME',
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = ph.CONTACT_ID and hemodialysis.DATE between ph.DATE_ADMITTED and ph.DATE_DISCHARGED) as TOTAL_TREATMENT '),
                DB::raw('(select cd.NAME from philhealth_prof_fee as pf join contact as cd on cd.ID = pf.CONTACT_ID where pf.PHIC_ID = ph.ID) as DOCTOR_NAME'),
            ])->join('contact as c', 'c.ID', '=', 'invoice.CUSTOMER_ID')
            ->join('philhealth as ph', 'ph.INVOICE_ID', '=', 'invoice.ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'invoice.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->whereYear('ph.DATE_DISCHARGED', '<=', 2025)
            ->where('invoice.BALANCE_DUE', '>', 0)
            ->whereNotNull('ph.AR_NO')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($sql) use (&$search) {
                    $sql->orWhere('invoice.CODE', 'like', '%' . $search . '%')
                        ->orWhere('invoice.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('invoice.PO_NUMBER', 'like', '%' . $search . '%');
                });
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('invoice_items')
                    ->whereColumn('invoice_items.INVOICE_ID', '=', 'invoice.ID')
                    ->where('invoice_items.ITEM_ID', '=', 2);
            })
            ->paginate(15);

        return $result;
    }

    public function getPaid(int $INVOICE_ID): float
    {
        $data = $this->get($INVOICE_ID);

        if ($data) {
            $TOTAL_PAYMENT = $this->GetPaymentApplied($INVOICE_ID);
            $TOTAL_CREDIT  = $this->GetCreditApplied($INVOICE_ID);
            $TOTAL_TAX     = $this->GetTaxCredit($INVOICE_ID);
            $TOTAL         = $TOTAL_PAYMENT + $TOTAL_CREDIT + $TOTAL_TAX;
            return $TOTAL;
        }
        return 0.00;
    }

    public function getPaymentIdVIaInvoice(int $INVOICE_ID)
    {
        $result = PaymentInvoices::where('INVOICE_ID', '=', $INVOICE_ID)->first();
        if ($result) {
            return $result->PAYMENT_ID;
        }
        return 0;
    }

    public function updateXero(int $INVOICE_ID, bool $IS_XERO): void
    {
        Invoice::where('ID', '=', $INVOICE_ID)
            ->update([
                'IS_XERO' => $IS_XERO,
            ]);
    }

    public function getInvoiceByXero(string $CODE)
    {
        $result = Invoice::query()
            ->select(['ID'])
            ->where('CODE', '=', $CODE)
            ->where('IS_XERO', '=', true)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }

        return 0;
    }

    public function listViaContact(int $CONTACT_ID)
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.CODE',
                'invoice.DATE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
                'invoice.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', 'l.ID', '=', 'invoice.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'invoice.STATUS')
            ->where('invoice.CUSTOMER_ID', '=', $CONTACT_ID)
            ->orderBy('invoice.DATE', 'desc')
            ->get();

        return $result;
    }
    public function getInvoiceByPatientDateRange($dateFrom, $dateTo, int $CONTACT_ID, int $LOCATION_ID, float $FIX_AMOUNT = 6350.00)
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.CODE',
                'invoice.DATE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
                'invoice.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', 'l.ID', '=', 'invoice.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'invoice.STATUS')
            ->where('invoice.CUSTOMER_ID', '=', $CONTACT_ID)
            ->whereBetween('invoice.DATE', [$dateFrom, $dateTo])
            ->where('invoice.LOCATION_ID', '=', $LOCATION_ID)
            ->where('invoice.AMOUNT', '=', $FIX_AMOUNT)
            ->orderBy('invoice.DATE', 'desc')
            ->get();

        return $result;
    }
}
