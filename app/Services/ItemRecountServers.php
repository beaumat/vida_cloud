<?php
namespace App\Services;

use App\Models\ItemRecount;

class ItemRecountServers
{
    private $dateServices;


    public function __construct(DateServices $dateServices)
    {
        $this->dateServices = $dateServices;
    }
    public function Insert(int $ITEM_ID, int $LOCATION_ID, string $DATE_ON): void
    {

        $isExist = ItemRecount::where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->exists();

        if (! $isExist) {

            ItemRecount::create([
                'ITEM_ID'     => $ITEM_ID,
                'LOCATION_ID' => $LOCATION_ID,
                'DATE_ON'     => $DATE_ON,
            ]);
        }

    }

}
