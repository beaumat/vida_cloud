<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItems;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;

class PurchaseOrderServices
{
    private $objectService;
    private $compute;
    private $dateServices;
    private $systemSettingServices;
    private $usersLogServices;
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        UsersLogServices $usersLogServices
    ) {
        $this->objectService         = $objectService;
        $this->compute               = $computeServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function get(int $ID): object
    {
        return PurchaseOrder::where('ID', $ID)->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $VENDOR_ID,
        int $LOCATION_ID,
        int $CLASS_ID,
        string $DATE_EXPECTED,
        string $SHIP_TO,
        int $SHIP_VIA_ID,
        int $PAYMENT_TERMS_ID,
        string $NOTES,
        int $STATUS,
        int $INPUT_TAX_ID,
        float $INPUT_TAX_RATE,
        int $INPUT_TAX_VAT_METHOD,
        int $INPUT_TAX_ACCOUNT_ID

    ): int {

        $ID          = (int) $this->objectService->ObjectNextID('PURCHASE_ORDER');
        $OBJECT_TYPE = (int) $this->objectService->ObjectTypeID('PURCHASE_ORDER');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        PurchaseOrder::create([
            'ID'                   => $ID,
            'RECORDED_ON'          => $this->dateServices->Now(),
            'CODE'                 => $CODE !== '' ? $CODE : $this->objectService->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                 => $DATE,
            'VENDOR_ID'            => $VENDOR_ID,
            'LOCATION_ID'          => $LOCATION_ID,
            'CLASS_ID'             => $CLASS_ID > 0 ? $CLASS_ID : null,
            'DATE_EXPECTED'        => $DATE_EXPECTED ? $DATE_EXPECTED : null,
            'SHIP_TO'              => $SHIP_TO ? $SHIP_TO : null,
            'SHIP_VIA_ID'          => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
            'PAYMENT_TERMS_ID'     => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
            'NOTES'                => $NOTES,
            'AMOUNT'               => 0,
            'STATUS'               => $STATUS,
            'INPUT_TAX_ID'         => $INPUT_TAX_ID ? $INPUT_TAX_ID : null,
            'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
            'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
            'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID > 0 ? $INPUT_TAX_ACCOUNT_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PURCHASE_ORDER, $ID);
        return $ID;
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        PurchaseOrder::where('ID', $ID)
            ->update(['STATUS' => $STATUS]);
        $this->usersLogServices->StatusLog($STATUS, LogEntity::PURCHASE_ORDER, $ID);
    }
    public function Update(
        int $ID,
        string $CODE,
        string $DATE,
        int $VENDOR_ID,
        int $LOCATION_ID,
        int $CLASS_ID,
        string $DATE_EXPECTED,
        string $SHIP_TO,
        int $SHIP_VIA_ID,
        int $PAYMENT_TERMS_ID,
        string $NOTES,
        int $STATUS,
        int $INPUT_TAX_ID,
        float $INPUT_TAX_RATE,
        int $INPUT_TAX_VAT_METHOD,
        int $INPUT_TAX_ACCOUNT_ID,
    ): void {

        PurchaseOrder::where('ID', $ID)
            ->update([
                'CODE'                 => $CODE,
                'DATE'                 => $DATE,
                'VENDOR_ID'            => $VENDOR_ID,
                'LOCATION_ID'          => $LOCATION_ID,
                'CLASS_ID'             => $CLASS_ID > 0 ? $CLASS_ID : null,
                'DATE_EXPECTED'        => $DATE_EXPECTED ? $DATE_EXPECTED : null,
                'SHIP_TO'              => $SHIP_TO ? $SHIP_TO : null,
                'SHIP_VIA_ID'          => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
                'PAYMENT_TERMS_ID'     => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
                'NOTES'                => $NOTES,
                'STATUS'               => $STATUS,
                'INPUT_TAX_ID'         => $INPUT_TAX_ID ? $INPUT_TAX_ID : null,
                'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
                'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
                'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID > 0 ? $INPUT_TAX_ACCOUNT_ID : null,
            ]);
        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::PURCHASE_ORDER, $ID);
    }

    public function Delete(int $ID): void
    {
        PurchaseOrderItems::where('PO_ID', $ID)->delete();
        PurchaseOrder::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PURCHASE_ORDER, $ID);
    }

    public function Search($search, int $LOCATION_ID, int $perPage): object
    {
        $result = PurchaseOrder::query()
            ->select([
                'purchase_order.ID',
                'purchase_order.CODE',
                'purchase_order.DATE',
                'purchase_order.AMOUNT',
                'purchase_order.INPUT_TAX_RATE',
                'purchase_order.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
            ])
            ->join('contact as c', 'c.ID', '=', 'purchase_order.VENDOR_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'purchase_order.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'purchase_order.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'purchase_order.INPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('purchase_order.CODE', 'like', '%' . $search . '%')
                    ->orWhere('purchase_order.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('purchase_order.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function UpdateItemBills(int $ID, int $QTY, bool $CLOSED)
    {
        PurchaseOrderItems::where('ID', $ID)
            ->update([
                'RECEIVED_QTY' => $QTY,
                'CLOSED'       => $CLOSED,
            ]);
    }

    public function CountItems(int $PO_ID): int
    {
        return PurchaseOrderItems::where('PO_ID', $PO_ID)->count();
    }
    private function getLine($Id): int
    {
        return (int) PurchaseOrderItems::where('PO_ID', $Id)->max('LINE_NO');
    }
    public function ItemStore(
        int $PO_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        float $RECEIVED_QTY,
        bool $CLOSED,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT
    ): int {

        $LINE_NO = $this->getLine($PO_ID) + 1;
        $ID      = $this->objectService->ObjectNextID('PURCHASE_ORDER_LINES');
        PurchaseOrderItems::create([
            'ID'                 => $ID,
            'PO_ID'              => $PO_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
            'DESCRIPTION'        => null,
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'               => $RATE,
            'RATE_TYPE'          => $RATE_TYPE,
            'AMOUNT'             => $AMOUNT,
            'RECEIVED_QTY'       => $RECEIVED_QTY > 0 ? $RECEIVED_QTY : 0,
            'CLOSED'             => $CLOSED,
            'TAXABLE'            => $TAXABLE,
            'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'         => $TAX_AMOUNT,

        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PURCHASE_ORDER_LINES, $PO_ID);
        return $ID;
    }
    public function ItemUpdate(
        int $ID,
        int $PO_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT
    ) {
        PurchaseOrderItems::where('ID', $ID)->where('PO_ID', $PO_ID)->where('ITEM_ID', $ITEM_ID)->update([
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'               => $RATE,
            'AMOUNT'             => $AMOUNT,
            'TAXABLE'            => $TAXABLE,
            'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'         => $TAX_AMOUNT,
        ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::PURCHASE_ORDER_LINES, $PO_ID);
    }
    public function ItemDelete(int $ID, int $PO_ID)
    {
        PurchaseOrderItems::where('ID', $ID)->where('PO_ID', $PO_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PURCHASE_ORDER_LINES, $PO_ID);
    }

    public function ItemGet(int $ID, int $PO_ID)
    {
        $result = PurchaseOrderItems::where('ID', $ID)->where('PO_ID', $PO_ID)->first();
        if ($result) {
            return $result;
        }
        return null;
    }
    public function ItemView(int $PO_ID)
    {
        return PurchaseOrderItems::query()
            ->select([
                'purchase_order_items.ID',
                'purchase_order_items.ITEM_ID',
                'purchase_order_items.PO_ID',
                'purchase_order_items.QUANTITY',
                'purchase_order_items.UNIT_ID',
                'purchase_order_items.RATE',
                'purchase_order_items.AMOUNT',
                'purchase_order_items.CLOSED',
                'purchase_order_items.TAXABLE',
                'purchase_order_items.TAXABLE_AMOUNT',
                'i.CODE',
                'i.PURCHASE_DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'purchase_order_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'purchase_order_items.UNIT_ID')
            ->where('PO_ID', $PO_ID)
            ->orderBy('purchase_order_items.LINE_NO', 'asc')
            ->get();
    }
    public function ReComputed(int $ID): array
    {
        $PO = PurchaseOrder::where('ID', $ID)->first();
        if ($PO) {
            $TAX_ID     = (int) $PO->INPUT_TAX_ID;
            $itemResult = PurchaseOrderItems::query()
                ->select(
                    [
                        'purchase_order_items.AMOUNT',
                        'purchase_order_items.TAX_AMOUNT',
                        'purchase_order_items.TAXABLE_AMOUNT',
                        'purchase_order_items.TAXABLE',
                        'item.TYPE',
                    ]
                )
                ->join('item', 'item.ID', '=', 'purchase_order_items.ITEM_ID')
                ->where('purchase_order_items.PO_ID', $ID)
                ->where('item.TYPE', 0)
                ->orderBy('purchase_order_items.LINE_NO', 'asc')
                ->get();

            $result = $this->compute->taxCompute($itemResult, $TAX_ID);

            foreach ($result as $list) {
                PurchaseOrder::where('ID', $ID)->update([
                    'AMOUNT'            => $list['AMOUNT'],
                    'INPUT_TAX_AMOUNT'  => $list['TAX_AMOUNT'],
                    'TAXABLE_AMOUNT'    => $list['TAXABLE_AMOUNT'],
                    'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT'],
                ]);
            }

            return $result;
        }

        return [];
    }

    public function getUpdateTaxItem(int $PO_ID, int $TAX_ID)
    {
        $items = PurchaseOrderItems::query()
            ->select([
                'purchase_order_items.ID',
                'purchase_order_items.AMOUNT',
                'purchase_order_items.TAXABLE',
            ])
            ->join('item', 'item.ID', '=', 'purchase_order_items.ITEM_ID')
            ->where('purchase_order_items.PO_ID', $PO_ID)
            ->where('item.TYPE', 0)
            ->orderBy('purchase_order_items.LINE_NO', 'asc')
            ->get();

        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;
        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                PurchaseOrderItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }
    }
    public function GetItemList(int $PO_ID)
    {
        $result = PurchaseOrderItems::query()
            ->select([
                'purchase_order_items.ID',
                'purchase_order_items.ITEM_ID',
                'purchase_order_items.DESCRIPTION',
                'purchase_order_items.QUANTITY',
                'purchase_order_items.UNIT_ID',
                'purchase_order_items.UNIT_BASE_QUANTITY',
                'purchase_order_items.RATE',
                'purchase_order_items.RATE_TYPE',
                'purchase_order_items.AMOUNT',
                'purchase_order_items.TAXABLE',
                'purchase_order_items.TAXABLE_AMOUNT',
                'purchase_order_items.TAX_AMOUNT',
                'purchase_order_items.RECEIVED_QTY',
                'purchase_order_items.CLOSED',
                'item.GL_ACCOUNT_ID',
                'item.COGS_ACCOUNT_ID',
                'item.ASSET_ACCOUNT_ID',
            ])
            ->join('item', 'item.ID', '=', 'purchase_order_items.ITEM_ID')
            ->where('PO_ID', $PO_ID)
            ->get();

        return $result;
    }
    public function PurchaseOrderAvailableList(int $VENDOR_ID, int $LOCATION_ID)
    {
        $result = PurchaseOrder::query()
            ->select(['ID', 'CODE', 'DATE', 'DATE_EXPECTED', 'AMOUNT'])
            ->where('VENDOR_ID', $VENDOR_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('STATUS', 15)
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }

    public function isPOAlreadyBill(int $PO_ID): bool
    {

        $result = DB::table('purchase_order as po')
            ->join('purchase_order_items as po_item', 'po_item.PO_ID', '=', 'po.ID')
            ->where('po.ID', '=', $PO_ID)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('bill as b')
                    ->join('bill_items as b_item', 'b_item.BILL_ID', '=', 'b.ID')
                    ->whereColumn('b_item.PO_ITEM_ID', '=', 'po_item.ID');
            })
            ->exists();

        return $result;
    }
}
