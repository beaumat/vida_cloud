<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\SalesReceipt;
use App\Models\SalesReceiptItems;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;

class SalesReceiptServices
{
    public int $object_type_sales_receipt       = 52;
    public int $object_type_sales_receipt_items = 53;
    public int $document_type_id                = 13;
    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;
    private $usersLogServices;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices, ComputeServices $computeServices, UsersLogServices $usersLogServices)
    {
        $this->object                = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->compute               = $computeServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function get(int $ID)
    {

        $data = SalesReceipt::where('ID', '=', $ID)->first();
        if ($data) {
            return $data;
        }
        return null;
    }
    public function getViaUndeposit(int $ID)
    {
        $result = SalesReceipt::where('ID', '=', $ID)
            ->where('DEPOSITED', '=', 0)
            ->first();

        if ($result) {
            return $result;
        }

        return [];
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        SalesReceipt::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::SALES_RECEIPT, $ID);
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        int $CLASS_ID = 0,
        int $SALES_REP_ID = 0,
        float $AMOUNT = 0,
        float $PAYMENT_AMOUNT,
        int $PAYMENT_METHOD_ID,
        string $PAYMENT_REF_NO,
        string $CARD_NO,
        int $CASH_COUNT_ID = 0,
        int $CASHIER_ID = 0,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        float $OUTPUT_TAX_AMOUNT,
        int $OUTPUT_TAX_VAT_METHOD,
        int $OUTPUT_TAX_ACCOUNT_ID,
        int $STATUS
    ): int {

        $ID          = (int) $this->object->ObjectNextID('SALES_RECEIPT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('SALES_RECEIPT');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        SalesReceipt::create([
            'ID'                           => $ID,
            'RECORDED_ON'                  => $this->dateServices->Now(),
            'CODE'                         => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                         => $DATE,
            'POS_TIMESTAMP'                => null,
            'CUSTOMER_ID'                  => $CUSTOMER_ID,
            'LOCATION_ID'                  => $LOCATION_ID,
            'CLASS_ID'                     => $CLASS_ID > 0 ? $CLASS_ID : null,
            'SALES_REP_ID'                 => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
            'AMOUNT'                       => $AMOUNT,
            'PAYMENT_AMOUNT'               => $PAYMENT_AMOUNT,
            'PAYMENT_METHOD_ID'            => $PAYMENT_METHOD_ID,
            'PAYMENT_REF_NO'               => $PAYMENT_REF_NO,
            'CARD_NO'                      => $CARD_NO,
            'CASHIER_ID'                   => $CASHIER_ID > 0 ? $CASHIER_ID : null,
            'CASH_COUNT_ID'                => $CASH_COUNT_ID > 0 ? $CASH_COUNT_ID : null,
            'NOTES'                        => $NOTES,
            'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID,
            'OUTPUT_TAX_ID'                => $OUTPUT_TAX_ID,
            'OUTPUT_TAX_RATE'              => $OUTPUT_TAX_RATE,
            'OUTPUT_TAX_AMOUNT'            => $OUTPUT_TAX_AMOUNT,
            'OUTPUT_TAX_VAT_METHOD'        => $OUTPUT_TAX_VAT_METHOD,
            'OUTPUT_TAX_ACCOUNT_ID'        => $OUTPUT_TAX_ACCOUNT_ID,
            'STATUS'                       => $STATUS,
            'DEPOSITED'                    => 0,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::SALES_RECEIPT, $ID);

        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        int $CLASS_ID = 0,
        int $SALES_REP_ID = 0,
        float $AMOUNT = 0,
        float $PAYMENT_AMOUNT,
        int $PAYMENT_METHOD_ID,
        string $PAYMENT_REF_NO,
        string $CARD_NO,
        int $CASH_COUNT_ID = 0,
        int $CASHIER_ID = 0,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        float $OUTPUT_TAX_AMOUNT,
        int $OUTPUT_TAX_VAT_METHOD,
        int $OUTPUT_TAX_ACCOUNT_ID,
        int $STATUS

    ) {

        SalesReceipt::where('ID', '=', $ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->update([
                'CODE'                         => $CODE,
                'CUSTOMER_ID'                  => $CUSTOMER_ID,
                'LOCATION_ID'                  => $LOCATION_ID,
                'CLASS_ID'                     => $CLASS_ID > 0 ? $CLASS_ID : null,
                'SALES_REP_ID'                 => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
                'AMOUNT'                       => $AMOUNT,
                'PAYMENT_AMOUNT'               => $PAYMENT_AMOUNT,
                'PAYMENT_METHOD_ID'            => $PAYMENT_METHOD_ID,
                'PAYMENT_REF_NO'               => $PAYMENT_REF_NO,
                'CARD_NO'                      => $CARD_NO,
                'CASHIER_ID'                   => $CASHIER_ID > 0 ? $CASHIER_ID : null,
                'CASH_COUNT_ID'                => $CASH_COUNT_ID > 0 ? $CASH_COUNT_ID : null,
                'NOTES'                        => $NOTES,
                'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID,
                'OUTPUT_TAX_ID'                => $OUTPUT_TAX_ID,
                'OUTPUT_TAX_RATE'              => $OUTPUT_TAX_RATE,
                'OUTPUT_TAX_AMOUNT'            => $OUTPUT_TAX_AMOUNT,
                'OUTPUT_TAX_VAT_METHOD'        => $OUTPUT_TAX_VAT_METHOD,
                'OUTPUT_TAX_ACCOUNT_ID'        => $OUTPUT_TAX_ACCOUNT_ID,
                'STATUS'                       => $STATUS,
                'DEPOSITED'                    => 0,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::SALES_RECEIPT, $ID);
    }
    public function Delete(int $ID)
    {
        SalesReceiptItems::where('SALES_RECEIPT_ID', '=', $ID)->delete();
        SalesReceipt::where('ID', '=', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::SALES_RECEIPT, $ID);
    }

    public function Search($search, int $locationId, int $perPage, $dateEntry)
    {
        $result = SalesReceipt::query()
            ->select([
                'sales_receipt.ID',
                'sales_receipt.CODE',
                'sales_receipt.DATE',
                'sales_receipt.AMOUNT',
                'sales_receipt.OUTPUT_TAX_RATE',
                'sales_receipt.NOTES',
                'sales_receipt.PAYMENT_REF_NO',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
                'sales_receipt.STATUS as STATUS_ID',
                'a.NAME as ACCOUNT_NAME',
            ])
            ->join('contact as c', 'c.ID', '=', 'sales_receipt.CUSTOMER_ID')
            ->leftJoin('account as a', 'a.ID', '=', 'sales_receipt.UNDEPOSITED_FUNDS_ACCOUNT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'sales_receipt.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'sales_receipt.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'sales_receipt.OUTPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('sales_receipt.CODE', 'like', '%' . $search . '%')
                        ->orWhere('sales_receipt.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('sales_receipt.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when($dateEntry, function ($query) use (&$dateEntry) {
                $query->where(function ($q) use (&$dateEntry) {
                    $q->where('sales_receipt.DATE', '=', $dateEntry);
                });
            })

            ->orderBy('sales_receipt.DATE', 'desc')
            ->paginate($perPage);

        return $result;
    }
    private function getLine($Id): int
    {
        return (int) SalesReceiptItems::where('SALES_RECEIPT_ID', $Id)->max('LINE_NO');
    }

    public function ItemStore(
        int $SALES_RECEIPT_ID,
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
        int $BATCH_ID,
        int $GROUP_LINE_ID,
        bool $PRINT_IN_FORMS,
        bool $DEPOSITED,
        int $PRICE_LEVEL_ID,
    ): int {

        $LINE_NO = $this->getLine($SALES_RECEIPT_ID) + 1;
        $ID      = $this->object->ObjectNextID('SALES_RECEIPT_ITEMS');

        SalesReceiptItems::create([
            'ID'                 => $ID,
            'SALES_RECEIPT_ID'   => $SALES_RECEIPT_ID,
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
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'GROUP_LINE_ID'      => $GROUP_LINE_ID > 0,
            'PRINT_IN_FORMS'     => $PRINT_IN_FORMS,
            'DEPOSITED'          => $DEPOSITED,
            'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::SALES_RECEIPT_ITEMS, $SALES_RECEIPT_ID);

        return $ID;
    }
    public function ItemGet(int $ID, int $SALES_RECEIPT_ID)
    {
        return SalesReceiptItems::where('ID', '=', $ID)
            ->where('SALES_RECEIPT_ID', '=', $SALES_RECEIPT_ID)
            ->first();
    }

    public function ItemUpdate(
        int $ID,
        int $SALES_RECEIPT_ID,
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

        $data = $this->ItemGet($ID, $SALES_RECEIPT_ID);

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

            $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::SALES_RECEIPT_ITEMS, $SALES_RECEIPT_ID);
        }
    }

    public function ItemDelete(int $ID, int $SALES_RECEIPT_ID)
    {
        SalesReceiptItems::where('ID', $ID)->where('SALES_RECEIPT_ID', $SALES_RECEIPT_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::SALES_RECEIPT_ITEMS, $SALES_RECEIPT_ID);
    }

    public function ItemView(int $SALES_RECEIPT_ID): object
    {
        $result = SalesReceiptItems::query()
            ->select([
                'sales_receipt_items.ID',
                'sales_receipt_items.ITEM_ID',
                'sales_receipt_items.SALES_RECEIPT_ID',
                'sales_receipt_items.QUANTITY',
                'sales_receipt_items.UNIT_ID',
                'sales_receipt_items.RATE',
                'sales_receipt_items.AMOUNT',
                'sales_receipt_items.TAXABLE',
                'sales_receipt_items.TAXABLE_AMOUNT',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                'c.DESCRIPTION as CLASS_DESCRIPTION',
                'sales_receipt_items.INCOME_ACCOUNT_ID',
                'a.NAME as ACCOUNT_NAME',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'sales_receipt_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'sales_receipt_items.UNIT_ID')
            ->leftJoin('item_sub_class as sl', 'sl.ID', '=', 'i.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 'sl.CLASS_ID')
            ->leftJoin('account as a', 'a.ID', '=', 'sales_receipt_items.INCOME_ACCOUNT_ID')
            ->where('sales_receipt_items.SALES_RECEIPT_ID', $SALES_RECEIPT_ID)
            ->orderBy('sales_receipt_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function ReComputed(int $ID): array
    {
        $salesReceipt = SalesReceipt::where('ID', $ID)->first();

        if ($salesReceipt) {
            $TAX_ID = (int) $salesReceipt->OUTPUT_TAX_ID;

            $itemResult = SalesReceiptItems::query()
                ->select(
                    [
                        'sales_receipt_items.AMOUNT',
                        'sales_receipt_items.TAX_AMOUNT',
                        'sales_receipt_items.TAXABLE_AMOUNT',
                        'sales_receipt_items.TAXABLE',
                        'item.TYPE',
                    ]
                )
                ->join('item', 'item.ID', '=', 'sales_receipt_items.ITEM_ID')
                ->where('sales_receipt_items.SALES_RECEIPT_ID', $ID)
                ->whereIn('item.TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
                ->orderBy('sales_receipt_items.LINE_NO', 'asc')
                ->get();

            // $totalPay = $salesReceipt->PAYMENT_AMOUNT ?? 0;

            $data = $this->compute->taxCompute($itemResult, $TAX_ID);
            foreach ($data as $list) {
                $originalAmount = (float) $list['AMOUNT'];

                SalesReceipt::where('ID', '=', $ID)
                    ->update([
                        'AMOUNT'            => $originalAmount,
                        'OUTPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                        'TAXABLE_AMOUNT'    => $list['TAXABLE_AMOUNT'],
                        'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT'],
                    ]);

                $result = [
                    [
                        'AMOUNT'            => $originalAmount,
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

    public function getUpdateTaxItem(int $SALES_RECEIPT_ID, int $TAX_ID)
    {
        $items = SalesReceiptItems::query()
            ->select([
                'sales_receipt_items.ID',
                'sales_receipt_items.AMOUNT',
                'sales_receipt_items.TAXABLE',
            ])
            ->join('item', 'item.ID', '=', 'sales_receipt_items.ITEM_ID')
            ->where('sales_receipt_items.SALES_RECEIPT_ID', '=', $SALES_RECEIPT_ID)
            ->whereIn('item.TYPE', ['0', '1'])
            ->orderBy('sales_receipt_items.LINE_NO', 'asc')
            ->get();

        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;

        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                SalesReceiptItems::where('ID', '=', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }
    }

    public function CountItems(int $SALES_RECEIPT_ID): int
    {
        return (int) SalesReceiptItems::where('SALES_RECEIPT_ID', $SALES_RECEIPT_ID)->count();
    }

    public function ItemInventory(int $SALES_RECEIPT_ID)
    {
        $result = SalesReceiptItems::query()
            ->select([
                'sales_receipt_items.ID',
                'sales_receipt_items.ITEM_ID',
                'sales_receipt_items.RATE',
                'sales_receipt_items.QUANTITY',
                'sales_receipt_items.UNIT_BASE_QUANTITY',
                DB::raw('(select ifnull(sum(p.CUSTOM_COST),0) from price_level_lines as p inner join location as l on l.PRICE_LEVEL_ID = p.PRICE_LEVEL_ID where l.ID = sales_receipt.LOCATION_ID  and p.ITEM_ID = sales_receipt_items.ITEM_ID) as COST'),
            ])
            ->join('item', 'item.ID', '=', 'sales_receipt_items.ITEM_ID')
            ->join('sales_receipt', 'sales_receipt.ID', '=', 'sales_receipt_items.SALES_RECEIPT_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('sales_receipt_items.SALES_RECEIPT_ID', $SALES_RECEIPT_ID)
            ->get();

        return $result;
    }
    public function getTaxJournal(int $SALES_RECEIPT_ID)
    {
        $result = SalesReceipt::query()
            ->select([
                'ID',
                'OUTPUT_TAX_ACCOUNT_ID as ACCOUNT_ID',
                'CUSTOMER_ID as SUBSIDIARY_ID',
                'OUTPUT_TAX_AMOUNT as AMOUNT',
                DB::raw(' 1 as ENTRY_TYPE'),

            ])
            ->where('ID', $SALES_RECEIPT_ID)
            ->get();

        return $result;
    }
    public function getJournal(int $SALES_RECEIPT_ID)
    {
        $result = SalesReceipt::query()
            ->select([
                'ID',
                'UNDEPOSITED_FUNDS_ACCOUNT_ID as ACCOUNT_ID',
                'CUSTOMER_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 0 as ENTRY_TYPE'),

            ])
            ->where('ID', '=', $SALES_RECEIPT_ID)
            ->get();

        return $result;
    }
    public function getInvoiceItemJournalIncome(int $SALES_RECEIPT_ID)
    {
        $result = SalesReceiptItems::query()
            ->select([
                'ID',
                'INCOME_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('SALES_RECEIPT_ID', '=', $SALES_RECEIPT_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function getInvoiceItemJournalAsset(int $SALES_RECEIPT_ID)
    {
        $result = SalesReceiptItems::query()
            ->select([
                'sales_receipt_items.ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('(select  ifnull(sum(p.CUSTOM_COST),0) from price_level_lines as p inner join location as l on l.PRICE_LEVEL_ID = p.PRICE_LEVEL_ID where l.ID = sales_receipt.LOCATION_ID  and p.ITEM_ID = sales_receipt_items.ITEM_ID) * sales_receipt_items.QUANTITY as AMOUNT'),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->join('sales_receipt', 'sales_receipt.ID', '=', 'sales_receipt_items.SALES_RECEIPT_ID')
            ->where('sales_receipt_items.SALES_RECEIPT_ID', $SALES_RECEIPT_ID)
            ->whereNotNull('ASSET_ACCOUNT_ID')
            ->orderBy('sales_receipt_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getInvoiceItemJournalCogs(int $SALES_RECEIPT_ID)
    {
        $result = SalesReceiptItems::query()
            ->select([
                'sales_receipt_items.ID',
                'sales_receipt_items.COGS_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('(select  ifnull(sum( p.CUSTOM_COST),0) from price_level_lines as p inner join location as l on l.PRICE_LEVEL_ID = p.PRICE_LEVEL_ID where l.ID = sales_receipt.LOCATION_ID  and p.ITEM_ID = sales_receipt_items.ITEM_ID) * sales_receipt_items.QUANTITY as AMOUNT'),
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->join('sales_receipt', 'sales_receipt.ID', '=', 'sales_receipt_items.SALES_RECEIPT_ID')
            ->where('sales_receipt_items.SALES_RECEIPT_ID', $SALES_RECEIPT_ID)
            ->whereNotNull('sales_receipt_items.COGS_ACCOUNT_ID')
            ->orderBy('sales_receipt_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function listViaContact(int $CONTACT_ID)
    {
        $result = SalesReceipt::query()
            ->select([
                'sales_receipt.ID',
                'sales_receipt.CODE',
                'sales_receipt.DATE',
                'sales_receipt.AMOUNT',
                'sales_receipt.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', 'l.ID', '=', 'sales_receipt.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'sales_receipt.STATUS')
            ->where('sales_receipt.CUSTOMER_ID', '=', $CONTACT_ID)
            ->orderBy('sales_receipt.DATE', 'desc')
            ->get();

        return $result;
    }
}
