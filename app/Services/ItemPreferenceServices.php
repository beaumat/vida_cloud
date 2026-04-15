<?php

namespace App\Services;

use App\Models\ItemPreference;

class ItemPreferenceServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function Store(int $ITEM_ID, int $LOCATION_ID, int $ORDER_POINT, float $ORDER_QTY, int $ORDER_LEADTIME, float $ONHAND_MAX_LIMIT, int $STOCK_BIN_ID): int
    {

        $ID = $this->object->ObjectNextID('ITEM_PREFERENCE');
        ItemPreference::create([
            'ID'                => $ID,
            'ITEM_ID'           => $ITEM_ID,
            'LOCATION_ID'       => $LOCATION_ID,
            'ORDER_POINT'       => $ORDER_POINT,
            'ORDER_QTY'         => $ORDER_QTY,
            'ORDER_LEADTIME'    => $ORDER_LEADTIME,
            'ONHAND_MAX_LIMIT'  => $ONHAND_MAX_LIMIT,
            'STOCK_BIN_ID'      => $STOCK_BIN_ID > 0 ? $STOCK_BIN_ID : null
        ]);

        return $ID;
    }

    public function Update(int $ID, int $ITEM_ID, float $ORDER_POINT, float $ORDER_QTY, int $ORDER_LEADTIME, float $ONHAND_MAX_LIMIT, int $STOCK_BIN_ID): void
    {
        ItemPreference::where('ID', $ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'ORDER_POINT'       => $ORDER_POINT,
                'ORDER_QTY'         => $ORDER_QTY,
                'ORDER_LEADTIME'    => $ORDER_LEADTIME,
                'ONHAND_MAX_LIMIT'  => $ONHAND_MAX_LIMIT,
                'STOCK_BIN_ID'      => $STOCK_BIN_ID > 0 ? $STOCK_BIN_ID : null
            ]);
    }

    public function Delete(int $ID): void
    {
        ItemPreference::where('ID', $ID)->delete();
    }
    public function Search(int $itemId)
    {
        $result = ItemPreference::query()
            ->select([
                'item_preference.ID',
                'location.NAME as LOCATION_NAME',
                'item_preference.ORDER_POINT',
                'item_preference.ORDER_QTY',
                'item_preference.ORDER_LEADTIME',
                'item_preference.ONHAND_MAX_LIMIT',
                'item_preference.STOCK_BIN_ID',
                'stock_bin.DESCRIPTION as STOCK_BIN_NAME'
            ])
            ->join('location', 'location.ID', '=', 'item_preference.LOCATION_ID')
            ->leftjoin('stock_bin', 'stock_bin.ID', '=', 'item_preference.STOCK_BIN_ID')
            ->where('item_preference.ITEM_ID', '=', $itemId)
            ->orderBy('item_preference.ID', 'asc')->get();

        return $result;
    }
}
