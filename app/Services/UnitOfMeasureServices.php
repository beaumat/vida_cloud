<?php

namespace App\Services;

use App\Models\Items;
use App\Models\ItemUnits;
use App\Models\UnitOfMeasures;
use Illuminate\Support\Facades\DB;

class UnitOfMeasureServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getList()
    {
        return UnitOfMeasures::where('INACTIVE', 0)->get();
    }
    public function get(int $ID)
    {
        return UnitOfMeasures::where('ID', $ID)->first();
    }
    public function Store(string $NAME, string $SYMBOL, bool $INACTIVE): int
    {
        $ID = $this->object->ObjectNextID('UNIT_OF_MEASURE');

        UnitOfMeasures::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'SYMBOL' => $SYMBOL,
            'INACTIVE' => $INACTIVE
        ]);

        return $ID;
    }

    public function Update(int $ID, string $NAME, string $SYMBOL, bool $INACTIVE): void
    {
        UnitOfMeasures::where('ID', $ID)->update([
            'NAME' => $NAME,
            'SYMBOL' => $SYMBOL,
            'INACTIVE' => $INACTIVE
        ]);
    }

    public function Delete(int $ID): void
    {
        UnitOfMeasures::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        if (!$search) {
            return UnitOfMeasures::orderBy('ID', 'desc')->get();
        } else {
            return UnitOfMeasures::where('NAME', 'like', '%' . $search . '%')
                ->orWhere('SYMBOL', 'like', '%' . $search . '%')
                ->orderBy('ID', 'desc')
                ->get();
        }
    }
    public function ItemUnit($ITEM_ID)
    {
        $result = Items::query()
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'item.BASE_UNIT_ID')
            ->select(['u.ID', 'u.SYMBOL'])
            ->where('item.ID', '=', $ITEM_ID)
            ->whereNotNull('u.ID') // Add condition to check if ID is not null
            ->unionAll(
                ItemUnits::query()
                    ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'item_units.UNIT_ID')
                    ->select(['u.ID', 'u.SYMBOL'])
                    ->where('item_units.ITEM_ID', '=', $ITEM_ID)
                    ->whereNotNull('u.ID') // Add condition to check if ID is not null
            )
            ->get();

            return $result;
    
    }
    public function GetItemUnitDetails(int $ITEM_ID, int $UNIT_ID)
    {
        $itemId = $ITEM_ID;
        $sId = $UNIT_ID;

        // Define the raw SQL query
        $query = "
    SELECT 
      s.ID,
      s.QUANTITY,
      s.RATE 
    FROM
      (
        (SELECT 
          u.ID,
          1 as QUANTITY,
          item.RATE 
        FROM
          item 
          LEFT JOIN unit_of_measure AS u 
            ON u.ID = item.BASE_UNIT_ID 
        WHERE item.ID = :itemId1) 
        UNION
        ALL 
        (SELECT 
          u.ID,
          item_units.QUANTITY,
          item_units.RATE 
        FROM
          item_units 
          LEFT JOIN unit_of_measure AS u 
            ON u.ID = item_units.UNIT_ID 
        WHERE item_units.ITEM_ID = :itemId2)
      ) as s 
    WHERE s.ID = :sId
";

        // Execute the query using DB::select with bindings
        $result = collect(DB::select($query, [
            'itemId1' => $itemId,
            'itemId2' => $itemId,
            'sId' => $sId,
        ]))->first();

        if ($result) {
            return [
                'QUANTITY' => $result->QUANTITY ?? 1,
                'RATE' => $result->RATE ?? 0
            ];
        }
        return [
            'QUANTITY' =>  1,
            'RATE' =>  0
        ];
    }
}
