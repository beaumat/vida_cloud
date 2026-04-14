<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\InventoryAdjustment;
use App\Models\InventoryAdjustmentItems;
use Illuminate\Support\Facades\DB;

class InventoryAdjustmentServices
{
    private $object;
    private $systemSettingServices;
    private $dateServices;
    private $itemInventoryServices;
    public $usersLogServices;

    public int $object_type_map_inventory_adjustment      = 19;
    public int $object_type_map_inventory_adjustmentItems = 20;
    public int $documentTypeMapId                         = 6;

    public function __construct(ObjectServices $objectService,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        ItemInventoryServices $itemInventoryServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                = $objectService;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->usersLogServices      = $usersLogServices;

    }
    public function Get(int $ID)
    {
        return InventoryAdjustment::where('ID', '=', $ID)->first();
    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID, int $ADJUSTMENT_TYPE_ID, int $ACCOUNT_ID, string $NOTES): int
    {
        $ID          = (int) $this->object->ObjectNextID('INVENTORY_ADJUSTMENT');

        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('INVENTORY_ADJUSTMENT');

        $isLocRef    = (bool) boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        InventoryAdjustment::create([
            'ID'                 => $ID,
            'RECORDED_ON'        => $this->dateServices->Now(),
            'CODE'               => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'               => $DATE,
            'LOCATION_ID'        => $LOCATION_ID,
            'ADJUSTMENT_TYPE_ID' => $ADJUSTMENT_TYPE_ID,
            'ACCOUNT_ID'         => $ACCOUNT_ID,
            'NOTES'              => $NOTES,
            'STATUS'             => 0,
            'STATUS_DATE'        => $this->dateServices->NowDate(),
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::INVENTORY_ADJUSTMENT, $ID);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        InventoryAdjustment::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::INVENTORY_ADJUSTMENT, $ID);
    }
    public function Update(int $ID, string $CODE, int $LOCATION_ID, int $ADJUSTMENT_TYPE_ID, int $ACCOUNT_ID, string $NOTES)
    {
        InventoryAdjustment::where('ID', $ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->update([
                'CODE'               => $CODE,
                'ADJUSTMENT_TYPE_ID' => $ADJUSTMENT_TYPE_ID,
                'ACCOUNT_ID'         => $ACCOUNT_ID,
                'NOTES'              => $NOTES,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::INVENTORY_ADJUSTMENT, $ID);
    }
    public function Delete(int $ID)
    {
        InventoryAdjustmentItems::where('INVENTORY_ADJUSTMENT_ID', $ID)->delete();
        InventoryAdjustment::where('ID', $ID)->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::INVENTORY_ADJUSTMENT, $ID);
    }
    public function Search($search, int $locationId, int $perPage)
    {
        $result = InventoryAdjustment::query()
            ->select([
                'inventory_adjustment.ID',
                'inventory_adjustment.CODE',
                'inventory_adjustment.DATE',
                'inventory_adjustment.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                't.DESCRIPTION as TYPE',
                'inventory_adjustment.STATUS as STATUS_ID',
            ])
            ->join('inventory_adjustment_type as t', 't.ID', '=', 'inventory_adjustment.ADJUSTMENT_TYPE_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'inventory_adjustment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'inventory_adjustment.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('inventory_adjustment.CODE', 'like', '%' . $search . '%')
                        ->orWhere('t.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('inventory_adjustment.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('inventory_adjustment.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function CountItems(int $INVENTORY_ADJUSTMENT_ID): int
    {
        return (int) InventoryAdjustmentItems::where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)->count();
    }
    private function getLine($INVENTORY_ADJUSTMENT_ID): int
    {
        return (int) InventoryAdjustmentItems::where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)->max('LINE_NO');
    }
    public function GetItem(int $ID, int $INVENTORY_ADJUSTMENT_ID)
    {
        return InventoryAdjustmentItems::where('ID', $ID)
            ->where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)
            ->first();
    }
    public function haveExists(int $INVENTORY_ADJUSTMENT_ID, int $ITEM_ID): bool
    {
        return InventoryAdjustmentItems::where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->exists();
    }
    public function ItemHasAdjustmentThatBefore(int $ITEM_ID, string $DATE, int $LOCATION_ID): bool
    {
        $data = InventoryAdjustmentItems::join('inventory_adjustment as i', 'i.ID', '=', 'inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID')
            ->where('ITEM_ID', $ITEM_ID)
            ->where('i.DATE', '>=', $DATE)
            ->where('i.LOCATION_ID', $LOCATION_ID)
            ->orderBy('i.DATE', 'desc')
            ->first();

        if ($data) {
            if ($data->DATE == $DATE) {
                return false;
            }
            return true;
        }

        return false;
    }

    private function GET_DIFF(int $INVENTORY_ADJUSTMENT_ID, int $ITEM_ID, float $MUST_QTY, float $totalNewCost, int $REF_ID)
    {
        $data = $this->Get($INVENTORY_ADJUSTMENT_ID);
        if ($data) {
            $dataList = $this->itemInventoryServices->getEndingLastOutPutAdjustment($ITEM_ID, $data->LOCATION_ID, $data->DATE, $REF_ID);

            $ENDING_QUANTITY = (float) $dataList['ENDING_QUANTITY'];
            $ENDING_COST     = (float) $dataList['ENDING_COST'];
            $QTY             = (float) $MUST_QTY - $ENDING_QUANTITY;

            $COST = (float) $totalNewCost - $ENDING_COST;
            return [
                'QTY_DIFFERENCE' => $QTY,
                'DIFF_COST'      => $COST,

            ];
        }
    }
    public function ItemStore(int $INVENTORY_ADJUSTMENT_ID, int $ITEM_ID, float $QUANTITY, float $UNIT_COST, int $ASSET_ACCOUNT_ID, int $BATCH_ID, int $UNIT_ID, float $UNIT_BASE_QUANTITY)
    {

        $ID         = (int) $this->object->ObjectNextID('INVENTORY_ADJUSTMENT_ITEMS');
        $LINE_NO    = (int) $this->getLine($INVENTORY_ADJUSTMENT_ID) + 1;
        $TOTAL_COST = $QUANTITY * $UNIT_COST;
        $QTY        = $QUANTITY * $UNIT_BASE_QUANTITY;
        $data       = $this->GET_DIFF($INVENTORY_ADJUSTMENT_ID, $ITEM_ID, $QTY, $TOTAL_COST, $ID);

        InventoryAdjustmentItems::create([
            'ID'                      => $ID,
            'INVENTORY_ADJUSTMENT_ID' => $INVENTORY_ADJUSTMENT_ID,
            'LINE_NO'                 => $LINE_NO,
            'ITEM_ID'                 => $ITEM_ID,
            'QUANTITY'                => $QUANTITY,
            'UNIT_COST'               => $UNIT_COST,
            'QTY_DIFFERENCE'          => $data['QTY_DIFFERENCE'],
            'VALUE_DIFFERENCE'        => $data['DIFF_COST'],
            'ASSET_ACCOUNT_ID'        => $ASSET_ACCOUNT_ID,
            'ASSET_VALUE'             => $TOTAL_COST,
            'BATCH_ID'                => $BATCH_ID > 0 ? $BATCH_ID : null,
            'UNIT_ID'                 => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY'      => $UNIT_BASE_QUANTITY,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::INVENTORY_ADJUSTMENT_ITEMS, $INVENTORY_ADJUSTMENT_ID);
    }
    public function ItemUpdate(int $ID, int $INVENTORY_ADJUSTMENT_ID, int $ITEM_ID, float $QUANTITY, float $UNIT_COST, int $BATCH_ID, int $UNIT_ID, float $UNIT_BASE_QUANTITY)
    {

        $TOTAL_COST = $QUANTITY * $UNIT_COST;
        $QTY        = $QUANTITY * $UNIT_BASE_QUANTITY;
        $data       = $this->GET_DIFF(
            $INVENTORY_ADJUSTMENT_ID,
            $ITEM_ID,
            $QTY,
            $TOTAL_COST,
            $ID
        );

        InventoryAdjustmentItems::where('ID', '=', $ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('INVENTORY_ADJUSTMENT_ID', '=', $INVENTORY_ADJUSTMENT_ID)
            ->update([
                'QUANTITY'           => $QUANTITY,
                'UNIT_COST'          => $UNIT_COST,
                'QTY_DIFFERENCE'     => $data['QTY_DIFFERENCE'],
                'VALUE_DIFFERENCE'   => $data['DIFF_COST'],
                'ASSET_VALUE'        => $TOTAL_COST,
                'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
                'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::INVENTORY_ADJUSTMENT_ITEMS, $INVENTORY_ADJUSTMENT_ID);
    }
    public function ItemDelete(int $ID, int $INVENTORY_ADJUSTMENT_ID)
    {

        InventoryAdjustmentItems::where('ID', '=', $ID)
            ->where('INVENTORY_ADJUSTMENT_ID', '=', $INVENTORY_ADJUSTMENT_ID)
            ->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::INVENTORY_ADJUSTMENT_ITEMS, $INVENTORY_ADJUSTMENT_ID);
    }

    public function ItemView(int $INVENTORY_ADJUSTMENT_ID)
    {
        $result = InventoryAdjustmentItems::query()
            ->select([
                'inventory_adjustment_items.ID',
                'inventory_adjustment_items.ITEM_ID',
                'inventory_adjustment_items.QUANTITY',
                'inventory_adjustment_items.UNIT_COST',
                'inventory_adjustment_items.UNIT_ID',
                'inventory_adjustment_items.QTY_DIFFERENCE',
                'inventory_adjustment_items.ASSET_VALUE',
                'inventory_adjustment_items.VALUE_DIFFERENCE',
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
            ])
            ->join('inventory_adjustment', 'inventory_adjustment.ID', '=', 'inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID')
            ->leftJoin('item', 'item.ID', '=', 'inventory_adjustment_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'inventory_adjustment_items.UNIT_ID')
            ->where('inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID', '=', $INVENTORY_ADJUSTMENT_ID)
            ->orderBy('inventory_adjustment_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function ItemInventory(int $INVENTORY_ADJUSTMENT_ID)
    {
        $result = InventoryAdjustmentItems::query()
            ->select([
                'inventory_adjustment_items.ID',
                'inventory_adjustment_items.ITEM_ID',
                'inventory_adjustment_items.QUANTITY',
                'inventory_adjustment_items.UNIT_BASE_QUANTITY',
                'inventory_adjustment_items.QTY_DIFFERENCE',
                'inventory_adjustment_items.UNIT_COST as COST',
            ])
            ->where('inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID', '=', $INVENTORY_ADJUSTMENT_ID)
            ->get();

        return $result;
    }
    public function getInventoryAdjustmentJournal(int $ID)
    {

        $result = InventoryAdjustment::query()
            ->select([
                'inventory_adjustment.ID',
                'inventory_adjustment.ACCOUNT_ID',
                'inventory_adjustment.ADJUSTMENT_TYPE_ID as SUBSIDIARY_ID',
                DB::raw('IF((SELECT IFNULL(SUM(inventory_adjustment_items.VALUE_DIFFERENCE), 0) FROM inventory_adjustment_items  WHERE inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID = inventory_adjustment.ID) >=0, (SELECT IFNULL(SUM(inventory_adjustment_items.VALUE_DIFFERENCE), 0) FROM inventory_adjustment_items  WHERE inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID = inventory_adjustment.ID) , (SELECT IFNULL(SUM(inventory_adjustment_items.VALUE_DIFFERENCE), 0) FROM inventory_adjustment_items  WHERE inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID = inventory_adjustment.ID)  * -1) as AMOUNT'),
                DB::raw('IF((SELECT IFNULL(SUM(inventory_adjustment_items.VALUE_DIFFERENCE), 0) FROM inventory_adjustment_items  WHERE inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID = inventory_adjustment.ID) >= 0, 1, 0) as ENTRY_TYPE'),
            ])
            ->where('inventory_adjustment.ID', '=', $ID)
            ->get();

        return $result;
    }
    public function getInventoryAdjustmentItemsJournal(int $ID)
    {
        $result = InventoryAdjustmentItems::query()
            ->select([
                'inventory_adjustment_items.ID',
                'inventory_adjustment_items.ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'inventory_adjustment_items.ITEM_ID as SUBSIDIARY_ID',
                DB::raw(' IF(IFNULL(inventory_adjustment_items.VALUE_DIFFERENCE, 0)>= 0 , IFNULL(inventory_adjustment_items.VALUE_DIFFERENCE, 0), IFNULL(inventory_adjustment_items.VALUE_DIFFERENCE, 0) * -1) as AMOUNT'),
                DB::raw('IF(IFNULL(inventory_adjustment_items.VALUE_DIFFERENCE, 0) >= 0, 0, 1) as ENTRY_TYPE'),
            ])
            ->where('inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}
