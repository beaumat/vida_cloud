<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
use App\Models\Tax;
use Livewire\WithPagination;

class SalesOrderServices
{

    use WithPagination;
    private $objectService;
    private $compute;
    private $locationReference;
    private $systemSettingServices;
    private $dateServices;

    private $usersLogServices;
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        LocationReferenceServices $locationReferenceServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        UsersLogServices $usersLogServices

    ) {
        $this->objectService         = $objectService;
        $this->compute               = $computeServices;
        $this->locationReference     = $locationReferenceServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->usersLogServices      = $usersLogServices;
    }

    public function get(int $ID)
    {
        return SalesOrder::where('ID', $ID)->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        int $CLASS_ID,
        int $SALES_REP_ID,
        $DATE_NEEDED,
        string $PO_NUMBER,
        string $SHIP_TO,
        int $SHIP_VIA_ID,
        int $PAYMENT_TERMS_ID,
        string $NOTES,
        int $STATUS,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        float $OUTPUT_TAX_AMOUNT,
        int $OUTPUT_TAX_VAT_METHOD
    ): int {

        $ID          = (int) $this->objectService->ObjectNextID('SALES_ORDER');
        $OBJECT_TYPE = (int) $this->objectService->ObjectTypeID('SALES_ORDER');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        SalesOrder::create([
            'ID'                    => $ID,
            'RECORDED_ON'           => $this->dateServices->Now(),
            'CODE'                  => $CODE !== '' ? $CODE : $this->objectService->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                  => $DATE,
            'CUSTOMER_ID'           => $CUSTOMER_ID,
            'LOCATION_ID'           => $LOCATION_ID,
            'CLASS_ID'              => $CLASS_ID > 0 ? $CLASS_ID : null,
            'SALES_REP_ID'          => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
            'DATE_NEEDED'           => $DATE_NEEDED ?? null,
            'PO_NUMBER'             => $PO_NUMBER ?? '',
            'SHIP_TO'               => $SHIP_TO ? $SHIP_TO : null,
            'SHIP_VIA_ID'           => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
            'PAYMENT_TERMS_ID'      => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
            'NOTES'                 => $NOTES ?? null,
            'AMOUNT'                => 0,
            'STATUS'                => $STATUS,
            'STATUS_DATE'           => $this->dateServices->NowDate(),
            'OUTPUT_TAX_ID'         => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
            'OUTPUT_TAX_RATE'       => $OUTPUT_TAX_RATE,
            'OUTPUT_TAX_AMOUNT'     => $OUTPUT_TAX_AMOUNT,
            'OUTPUT_TAX_VAT_METHOD' => $OUTPUT_TAX_VAT_METHOD,

        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::SALES_ORDER, $ID);
        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        string $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        int $CLASS_ID,
        int $SALES_REP_ID,
        $DATE_NEEDED,
        string $PO_NUMBER,
        string $SHIP_TO,
        int $SHIP_VIA_ID,
        int $PAYMENT_TERMS_ID,
        string $NOTES,
        int $STATUS,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        float $OUTPUT_TAX_AMOUNT,
        int $OUTPUT_TAX_VAT_METHOD

    ) {

        SalesOrder::where('ID', $ID)
            ->update([
                'CODE'                  => $CODE,
                'DATE'                  => $DATE,
                'CUSTOMER_ID'           => $CUSTOMER_ID,
                'LOCATION_ID'           => $LOCATION_ID,
                'CLASS_ID'              => $CLASS_ID > 0 ? $CLASS_ID : null,
                'SALES_REP_ID'          => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
                'DATE_NEEDED'           => $DATE_NEEDED ?? null,
                'PO_NUMBER'             => $PO_NUMBER ?? '',
                'SHIP_TO'               => $SHIP_TO ? $SHIP_TO : null,
                'SHIP_VIA_ID'           => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
                'PAYMENT_TERMS_ID'      => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
                'NOTES'                 => $NOTES ?? null,
                'OUTPUT_TAX_ID'         => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
                'OUTPUT_TAX_RATE'       => $OUTPUT_TAX_RATE,
                'OUTPUT_TAX_AMOUNT'     => $OUTPUT_TAX_AMOUNT,
                'OUTPUT_TAX_VAT_METHOD' => $OUTPUT_TAX_VAT_METHOD,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::SALES_ORDER, $ID);
    }
    public function Delete(int $ID)
    {
        SalesOrderItems::where('SALES_ORDER_ID', $ID)->delete();
        SalesOrder::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::SALES_ORDER, $ID);
    }
    public function Search($search, int $locationId, int $perPage)
    {
        return SalesOrder::query()
            ->select([
                'sales_order.ID',
                'sales_order.CODE',
                'sales_order.DATE',
                'sales_order.AMOUNT',
                'sales_order.OUTPUT_TAX_RATE',
                'sales_order.NOTES',
                'sales_order.STATUS as STATUS_ID',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
            ])
            ->join('contact as c', 'c.ID', '=', 'sales_order.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'sales_order.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'sales_order.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'sales_order.OUTPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('sales_order.CODE', 'like', '%' . $search . '%')
                    ->orWhere('sales_order.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('sales_order.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('sales_order.ID', 'desc')
            ->paginate($perPage);
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        SalesOrder::where('ID', $ID)->update([
            'STATUS'      => $STATUS,
            'STATUS_DATE' => $this->dateServices->NowDate(),
        ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::SALES_ORDER, $ID);
    }
    private function getLine($Id): int
    {
        return (int) SalesOrderItems::where('SALES_ORDER_ID', $Id)->max('LINE_NO');
    }
    public function ItemStore(
        int $SALES_ORDER_ID,
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
        int $BATCH_ID,
        int $GROUP_LINE_ID,
        bool $PRINT_IN_FORMS,
        int $PRICE_LEVEL_ID,

    ): int {

        $LINE_NO = $this->getLine($SALES_ORDER_ID) + 1;
        $ID      = $this->objectService->ObjectNextID('SALES_ORDER_ITEMS');

        SalesOrderItems::create([
            'ID'                 => $ID,
            'SALES_ORDER_ID'     => $SALES_ORDER_ID,
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
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'GROUP_LINE_ID'      => $GROUP_LINE_ID > 0,
            'PRINT_IN_FORMS'     => $PRINT_IN_FORMS,
            'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'INVOICED_QTY'       => 0,
            'ESTIMATE_LINE_ID'   => null,
            'CLOSED'             => 0,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::SALES_ORDER_ITEMS, $SALES_ORDER_ID);
        return $ID;
    }
    public function ItemUpdate(
        int $ID,
        int $SALES_ORDER_ID,
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
        int $BATCH_ID,
        int $PRICE_LEVEL_ID,
    ) {
        SalesOrderItems::where('ID', $ID)->where('SALES_ORDER_ID', $SALES_ORDER_ID)->where('ITEM_ID', $ITEM_ID)->update([
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'               => $RATE,
            'AMOUNT'             => $AMOUNT,
            'TAXABLE'            => $TAXABLE,
            'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'         => $TAX_AMOUNT,
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::SALES_ORDER_ITEMS, $SALES_ORDER_ID);
    }
    public function ItemDelete(int $ID, int $SALES_ORDER_ID)
    {
        SalesOrderItems::where('ID', $ID)->where('SALES_ORDER_ID', $SALES_ORDER_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::SALES_ORDER_ITEMS, $SALES_ORDER_ID);
    }
    public function UpdateItemInvoice(int $ID, int $INVOICED_QTY, bool $CLOSED)
    {
        SalesOrderItems::where('ID', $ID)
            ->update([
                'INVOICED_QTY' => $INVOICED_QTY,
                'CLOSED'       => $CLOSED,
            ]);
    }
    public function CountItems(int $SALES_ORDER_ID): int
    {
        return SalesOrderItems::where('SALES_ORDER_ID', $SALES_ORDER_ID)->count();
    }
    public function GetItemList(int $SALES_ORDER_ID)
    {
        $result = SalesOrderItems::query()
            ->select([
                'sales_order_items.ID',
                'sales_order_items.ITEM_ID',
                'sales_order_items.DESCRIPTION',
                'sales_order_items.BATCH_ID',
                'sales_order_items.QUANTITY',
                'sales_order_items.UNIT_ID',
                'sales_order_items.UNIT_BASE_QUANTITY',
                'sales_order_items.RATE',
                'sales_order_items.RATE_TYPE',
                'sales_order_items.AMOUNT',
                'sales_order_items.TAXABLE',
                'sales_order_items.TAXABLE_AMOUNT',
                'sales_order_items.TAX_AMOUNT',
                'sales_order_items.ESTIMATE_LINE_ID',
                'sales_order_items.INVOICED_QTY',
                'sales_order_items.CLOSED',
                'sales_order_items.GROUP_LINE_ID',
                'sales_order_items.PRINT_IN_FORMS',
                'sales_order_items.PRICE_LEVEL_ID',
                'item.GL_ACCOUNT_ID',
                'item.COGS_ACCOUNT_ID',
                'item.ASSET_ACCOUNT_ID',
            ])
            ->join('item', 'item.ID', '=', 'sales_order_items.ITEM_ID')
            ->where('sales_order_items.SALES_ORDER_ID', $SALES_ORDER_ID)
            ->get();

        return $result;
    }
    public function ItemView(int $SALES_ORDER_ID)
    {
        return SalesOrderItems::query()
            ->select([
                'sales_order_items.ID',
                'sales_order_items.ITEM_ID',
                'sales_order_items.SALES_ORDER_ID',
                'sales_order_items.QUANTITY',
                'sales_order_items.UNIT_ID',
                'sales_order_items.RATE',
                'sales_order_items.AMOUNT',
                'sales_order_items.TAXABLE',
                'sales_order_items.TAXABLE_AMOUNT',
                'sales_order_items.INVOICED_QTY',
                'sales_order_items.CLOSED',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                'c.DESCRIPTION as CLASS_DESCRIPTION',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'sales_order_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'sales_order_items.UNIT_ID')
            ->leftJoin('item_sub_class as sl', 'sl.ID', '=', 'i.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 'sl.CLASS_ID')
            ->where('sales_order_items.SALES_ORDER_ID', $SALES_ORDER_ID)
            ->orderBy('sales_order_items.LINE_NO', 'asc')
            ->get();
    }

    public function getUpdateTaxItem(int $SALES_ORDER_ID, int $TAX_ID)
    {
        $items = SalesOrderItems::query()
            ->select([
                'sales_order_items.ID',
                'sales_order_items.AMOUNT',
                'sales_order_items.TAXABLE',
            ])
            ->join('item', 'item.ID', '=', 'sales_order_items.ITEM_ID')
            ->where('sales_order_items.SALES_ORDER_ID', $SALES_ORDER_ID)
            ->where('item.TYPE', 0)
            ->orderBy('sales_order_items.LINE_NO', 'asc')
            ->get();

        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;
        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                SalesOrderItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }
    }
    public function ReComputed(int $ID): array
    {
        $data = SalesOrder::where('ID', $ID)->first();
        if ($data) {
            $TAX_ID = (int) $data->OUTPUT_TAX_ID;

            $itemResult = SalesOrderItems::query()
                ->select(
                    [
                        'sales_order_items.AMOUNT',
                        'sales_order_items.TAX_AMOUNT',
                        'sales_order_items.TAXABLE_AMOUNT',
                        'sales_order_items.TAXABLE',
                        'item.TYPE',
                    ]
                )
                ->join('item', 'item.ID', '=', 'sales_order_items.ITEM_ID')
                ->where('sales_order_items.SALES_ORDER_ID', $ID)
                ->whereIn('item.TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
                ->orderBy('sales_order_items.LINE_NO', 'asc')
                ->get();

            $data = $this->compute->taxCompute($itemResult, $TAX_ID);

            foreach ($data as $list) {
                $originalAmount = (float) $list['AMOUNT'];

                SalesOrder::where('ID', $ID)->update([
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
    public function SalesOrderListAvailable(int $CUSTOMER_ID, int $LOCATION_ID)
    {
        $result = SalesOrder::query()
            ->select(['ID', 'CODE', 'DATE', 'DATE_NEEDED', 'AMOUNT'])
            ->where('CUSTOMER_ID', $CUSTOMER_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('STATUS', 0)
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }

}
