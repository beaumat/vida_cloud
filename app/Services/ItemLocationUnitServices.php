<?php

namespace App\Services;

use App\Models\ItemLocationUnits;

class ItemLocationUnitServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Store(int $ITEM_ID, int $LOCATION_ID, int $PURCHASES_UNIT_ID, int $SALES_UNIT_ID, int $SHIPPING_UNIT_ID): int
    {
        $ID = $this->object->ObjectNextID('ITEM_LOCATION_UNITS');

        ItemLocationUnits::create([
            'ID'                    => $ID,
            'ITEM_ID'               => $ITEM_ID,
            'LOCATION_ID'           => $LOCATION_ID,
            'PURCHASES_UNIT_ID'     => $PURCHASES_UNIT_ID,
            'SALES_UNIT_ID'         => $SALES_UNIT_ID,
            'SHIPPING_UNIT_ID'      => $SHIPPING_UNIT_ID

        ]);

        return $ID;
    }
    public function Update(int $ID, int $PURCHASES_UNIT_ID, int $SALES_UNIT_ID, int $SHIPPING_UNIT_ID): void
    {
        ItemLocationUnits::where('ID', $ID)->update([
            'PURCHASES_UNIT_ID'     => $PURCHASES_UNIT_ID,
            'SALES_UNIT_ID'         => $SALES_UNIT_ID,
            'SHIPPING_UNIT_ID'      => $SHIPPING_UNIT_ID
        ]);
    }
    public function Delete(int $ID)
    {
        ItemLocationUnits::where('ID', $ID)->delete();
    }
    public function Search(int $ITEM_ID)
    {
        $result = ItemLocationUnits::query()
            ->select([
                'item_location_units.ID',
                'item_location_units.LOCATION_ID',
                'item_location_units.PURCHASES_UNIT_ID',
                'item_location_units.SALES_UNIT_ID',
                'item_location_units.SHIPPING_UNIT_ID',
                'location.NAME as LOCATION_NAME',
                'u1.NAME as PURCHASES_UNIT',
                'u2.NAME as SALES_UNIT',
                'u3.NAME as SHIPPING_UNIT'
            ])
            ->join('location', 'location.ID', '=', 'item_location_units.LOCATION_ID')
            ->leftJoin('unit_of_measure as u1', 'u1.ID', '=', 'item_location_units.PURCHASES_UNIT_ID')
            ->leftJoin('unit_of_measure as u2', 'u2.ID', '=', 'item_location_units.SALES_UNIT_ID')
            ->leftJoin('unit_of_measure as u3', 'u3.ID', '=', 'item_location_units.SHIPPING_UNIT_ID')
            ->where('item_location_units.ITEM_ID', $ITEM_ID)
            ->where('location.INACTIVE', '0')
            ->where('u1.INACTIVE', '0')
            ->where('u2.INACTIVE', '0')
            ->where('u3.INACTIVE', '0')
            ->orderBy('item_location_units.ID', 'asc')
            ->get();

        return $result;
    }
}
