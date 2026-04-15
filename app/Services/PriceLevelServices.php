<?php

namespace App\Services;

use App\Models\PriceLevels;

class PriceLevelServices
{

    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getDropdown()
    {
        return PriceLevels::query()->select('ID', 'DESCRIPTION')->where('INACTIVE', '0')->get();
    }
    public function Store(string $CODE, string $DESCRIPTION, int $TYPE, float $RATE, int $ITEM_GROUP_ID, bool $INACTIVE): int
    {
        $ID = $this->object->ObjectNextID('PRICE_LEVEL');
        PriceLevels::create([
            'ID'            => $ID,
            'CODE'          => $CODE,
            'DESCRIPTION'   => $DESCRIPTION,
            'TYPE'          => $TYPE,
            'RATE'          => $RATE > 0 ? $RATE : null,
            'ITEM_GROUP_ID' => $ITEM_GROUP_ID === 0 ? null : $ITEM_GROUP_ID,
            'INACTIVE'      => $INACTIVE
        ]);
        
        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION, int $TYPE, float $RATE, int $ITEM_GROUP_ID, bool $INACTIVE)
    {
        PriceLevels::where('ID', $ID)->update([
            'CODE'          => $CODE,
            'DESCRIPTION'   => $DESCRIPTION,
            'TYPE'          => $TYPE,
            'RATE'          => $RATE > 0 ? $RATE : null,
            'ITEM_GROUP_ID' => $ITEM_GROUP_ID === 0 ? null : $ITEM_GROUP_ID,
            'INACTIVE'      => $INACTIVE
        ]);
    }
 
    public function Delete(int $ID): void
    {
        PriceLevels::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        if (!$search) {
            return PriceLevels::query()
                ->select([
                    'price_level.ID',
                    'price_level.CODE',
                    'price_level.DESCRIPTION',
                    'price_level_type_map.DESCRIPTION as TYPE',
                    'price_level_type_map.ID as TYPE_ID',
                    'price_level.RATE',
                    'item_group.DESCRIPTION as ITEM_GROUP',
                    'price_level.INACTIVE'

                ])
                ->join('price_level_type_map', 'price_level_type_map.ID', '=', 'price_level.TYPE')
                ->leftJoin('item_group', 'item_group.ID', '=', 'price_level.ITEM_GROUP_ID')
                ->orderBy('price_level.ID', 'desc')->get();
        } else {
            return PriceLevels::query()
                ->select([
                    'price_level.ID',
                    'price_level.CODE',
                    'price_level.DESCRIPTION',
                    'price_level_type_map.DESCRIPTION as TYPE',
                    'price_level_type_map.ID as TYPE_ID',
                    'price_level.RATE',
                    'item_group.DESCRIPTION as ITEM_GROUP',
                    'price_level.INACTIVE'

                ])
                ->join('price_level_type_map', 'price_level_type_map.ID', '=', 'price_level.TYPE')
                ->leftJoin('item_group', 'item_group.ID', '=', 'price_level.ITEM_GROUP_ID')
                ->orWhere('price_level.CODE', 'like', '%' . $search . '%')
                ->orWhere('price_level.DESCRIPTION', 'like', '%' . $search . '%')
                ->orWhere('price_level_type_map.DESCRIPTION', 'like', '%' . $search . '%')
                ->orWhere('price_level.RATE', 'like', '%' . $search . '%')
                ->orWhere('item_group.DESCRIPTION', 'like', '%' . $search . '%')
                ->orderBy('price_level.ID', 'desc')
                ->get();
        }
    }
}
