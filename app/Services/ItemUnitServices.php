<?php

namespace App\Services;

use App\Models\ItemUnits;

class ItemUnitServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Store(int $ITEM_ID, int $UNIT_ID, float $QUANTITY, float $RATE, string $BARCODE)
    {   
    
        $ID = $this->object->ObjectNextID('ITEM_UNITS');

        ItemUnits::create([
            'ID'        => $ID,
            'ITEM_ID'   => $ITEM_ID,
            'UNIT_ID'   => $UNIT_ID,
            'QUANTITY'  => $QUANTITY,
            'RATE'      => $RATE ,
            'BARCODE'   => $BARCODE
        ]);
    }
    public function Update(int $ID, float $QUANTITY, float $RATE, string $BARCODE)
    {
        ItemUnits::where('ID', $ID)->update([
            'QUANTITY'  => $QUANTITY,
            'RATE'      => $RATE,
            'BARCODE'   => $BARCODE
        ]);
    }
    public function Delete(int $ID)
    {
        ItemUnits::where('ID', $ID)->delete();
    }
    public function Search(int $ITEM_ID)
    {
        return ItemUnits::query()
            ->select([
                'item_units.ID',
                'item_units.UNIT_ID',
                'unit_of_measure.NAME',
                'unit_of_measure.SYMBOL',
                'item_units.QUANTITY',
                'item_units.RATE',
                'item_units.BARCODE'
            ])
            ->join('unit_of_measure', 'unit_of_measure.ID', '=', 'item_units.UNIT_ID')
            ->where('item_units.ITEM_ID', $ITEM_ID)
            ->where('unit_of_measure.INACTIVE', '0')
            ->orderBy('item_units.ID', 'asc')
            ->get();
    }
    public function RelatedUnitList(int $ITEM_ID)
    {
        return ItemUnits::query()
            ->select([
                'item_units.ID',
                'unit_of_measure.NAME',
            ])
            ->join('unit_of_measure', 'unit_of_measure.ID', '=', 'item_units.UNIT_ID')
            ->where('item_units.ITEM_ID', $ITEM_ID)
            ->where('unit_of_measure.INACTIVE', '0')
            ->orderBy('item_units.ID', 'asc')
            ->get();
    }
}
