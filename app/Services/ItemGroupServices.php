<?php

namespace App\Services;

use App\Models\ItemGroup;

class ItemGroupServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Store(string $CODE, string $DESCRIPTION, int $ITEM_TYPE): int
    {
        $ID = (int)  $this->object->ObjectNextID('ITEM_GROUP');
        ItemGroup::create([
            'ID' => $ID,
            'CODE' => $CODE,
            'DESCRIPTION' => $DESCRIPTION,
            'ITEM_TYPE' =>  $ITEM_TYPE
        ]);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION, int $ITEM_TYPE): void
    {
        ItemGroup::where('ID', $ID)->update([
            'CODE'          => $CODE,
            'DESCRIPTION'   => $DESCRIPTION,
            'ITEM_TYPE'     => $ITEM_TYPE
        ]);
    }
    public function Delete(int $ID): void
    {
        ItemGroup::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        $result = ItemGroup::query()
            ->select([
                'item_group.ID',
                'item_group.CODE',
                'item_group.DESCRIPTION',
                'item_type_map.DESCRIPTION as ITEM_TYPE'
            ])
            ->join('item_type_map', 'item_type_map.ID', '=', 'item_group.item_type')
            ->when($search, function ($query) use (&$search) {
                $query->where('item_group.CODE', 'like', '%' . $search . '%')
                    ->orWhere('item_group.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('item_type_map.DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->orderBy('item_group.ID', 'desc')
            ->get();
            
        return $result;
    }
    
}
