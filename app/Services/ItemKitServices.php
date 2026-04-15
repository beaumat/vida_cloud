<?php

namespace App\Services;

use App\Models\ItemKits;

class ItemKitServices
{

    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }

    public function Get(int $ID)
    {
        $result = ItemKits::where('ID', '=', $ID)->first();

        if ($result) {

            return $result;
        }

        return null;
    }
    public function Store(int $ITEM_ID, int $COMPONENT_ID, int $LOCATION_ID,  float $QUANTITY)
    {
        $ID = (int) $this->object->ObjectNextID('ITEM_KITS');

        ItemKits::create([
            'ID'            => $ID,
            'ITEM_ID'       => $ITEM_ID,
            'COMPONENT_ID'  => $COMPONENT_ID,
            'LOCATION_ID'   => $LOCATION_ID,
            'QUANTITY'      => $QUANTITY,
        ]);
    }
    public function Update(int $ID,  float $QUANTITY)
    {
        ItemKits::where('ID', '=', $ID)
            ->update([
                'QUANTITY'      => $QUANTITY,
            ]);
    }
    public function Delete(int $ID)
    {
        ItemKits::where('ID', '=', $ID)->delete();
    }
    public function List(int $ITEM_ID, int $LOCATION_ID)
    {

        $result = ItemKits::select(
            [
                'item_kits.ID',
                'item_kits.ITEM_ID',
                'item_kits.COMPONENT_ID',
                'item_kits.LOCATION_ID',
                'item_kits.QUANTITY',
                'c.DESCRIPTION as DESCRIPTION',
                'c.CODE as CODE'
            ]
        )->join('item as c', 'c.ID', '=', 'item_kits.COMPONENT_ID')
            ->where('item_kits.ITEM_ID', '=', $ITEM_ID)
            ->where('item_kits.LOCATION_ID', '=', $LOCATION_ID)
            ->get();

        return $result;
    }
}
