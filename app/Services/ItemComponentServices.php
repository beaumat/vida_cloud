<?php

namespace App\Services;

use App\Models\ItemComponents;

class ItemComponentServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Store(int $COMPONENT_ID, int  $ITEM_ID, float $QUANTITY, float $RATE): int
    {
        $ID = $this->object->ObjectNextID('ITEM_COMPONENTS');

        ItemComponents::create([
            'ID'            => $ID,
            'ITEM_ID'       => $ITEM_ID,
            'COMPONENT_ID'  => $COMPONENT_ID,
            'QUANTITY'      => $QUANTITY,
            'RATE'          => $RATE
        ]);

        return $ID;
    }
    public function Update(int $ID, float $QUANTITY, float $RATE): void
    {
        ItemComponents::where('ID', $ID)->update([
            'QUANTITY'  => $QUANTITY,
            'RATE'      => $RATE
        ]);
    }

    public function Delete(int $ID): void
    {
        ItemComponents::where('ID', $ID)->delete();
    }
    public function Search($search, int $itemId)
    {
        $result =  ItemComponents::query()
            ->select(['ITEM_COMPONENTS.ID', 'item.CODE', 'item.DESCRIPTION', 'ITEM_COMPONENTS.QUANTITY', 'ITEM_COMPONENTS.RATE'])
            ->leftjoin('item', 'item.ID', '=', 'ITEM_COMPONENTS.COMPONENT_ID')
            ->where('ITEM_COMPONENTS.ITEM_ID', $itemId)
            ->where('item.INACTIVE', '0')
            ->when($search, function ($query) use (&$search) {
                $query->where('item.CODE', 'like', '%' . $search . '%')
                    ->orWhere('item.DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->orderBy('ITEM_COMPONENTS.ID', 'asc')
            ->get();

        return $result;
    }
}
