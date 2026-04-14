<?php

namespace App\Services;

use App\Models\ItemClass;

class ItemClassServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function GetDesc($ID)
    {

        if ($ID) {
            return ItemClass::where('ID', $ID)->first()->DESCRIPTION;
        }
        
        return '';
    }
    public function Store(string $CODE, string $DESCRIPTION): int
    {
        $ID = $this->object->ObjectNextID('ITEM_CLASS');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('ITEM_CLASS');

        ItemClass::create([
            'ID'            => $ID,
            'CODE'          => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DESCRIPTION'   => $DESCRIPTION
        ]);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION): void
    {
        ItemClass::where('ID', $ID)->update([
            'CODE'           => $CODE,
            'DESCRIPTION'    => $DESCRIPTION
        ]);
    }

    public function Delete(int $ID): void
    {
        ItemClass::where('ID', $ID)->delete();
    }
    public function Search($search)
    {

        return ItemClass::query()
            ->when($search, function ($query) use (&$search) {
                $query->where('CODE', 'like', '%' . $search . '%')
                    ->orWhere('DESCRIPTION', 'like', '%' . $search . '%');
            })

            ->orderBy('ID', 'desc')
            ->get();
    }
}
