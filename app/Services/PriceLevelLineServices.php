<?php
namespace App\Services;

use App\Models\PriceLevelLines;

class PriceLevelLineServices
{
    private $object;
    private $locationServices;
    public function __construct(ObjectServices $objectService, LocationServices $locationServices)
    {
        $this->object           = $objectService;
        $this->locationServices = $locationServices;
    }
    public function IS_EXIST($ITEM_ID, int $LOCATION_ID): int
    {
        $locDate = $this->locationServices->get($LOCATION_ID);
        if ($locDate) {
            $PRICE_LEVEL_ID = $locDate->PRICE_LEVEL_ID ?? 0;

            $result = PriceLevelLines::select(['ID'])
                ->where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                ->where('ITEM_ID', $ITEM_ID)
                ->first();

            if ($result) {
                return (int) $result->ID ?? 0;
            }
        }

        return 0;
    }
    public function Store(int $PRICE_LEVEL_ID, int $ITEM_ID, float $CUSTOM_PRICE, float $CUSTOM_COST = 0): int
    {
        $ID = $this->object->ObjectNextID('PRICE_LEVEL_LINES');
        PriceLevelLines::create([
            'ID'             => $ID,
            'PRICE_LEVEL_ID' => $PRICE_LEVEL_ID,
            'ITEM_ID'        => $ITEM_ID,
            'CUSTOM_PRICE'   => $CUSTOM_PRICE,
            'CUSTOM_COST'    => $CUSTOM_COST,
        ]);

        return $ID;
    }
    public function Update(int $ID, float $CUSTOM_PRICE = 0, float $CUSTOM_COST = 0): void
    {

        if ($CUSTOM_PRICE == 0 && $CUSTOM_COST > 0) {
            PriceLevelLines::where('ID', '=', $ID)
                ->update([
                    'CUSTOM_COST' => $CUSTOM_COST,
                ]);

            return;
        }
        if ($CUSTOM_PRICE > 0 && $CUSTOM_COST == 0) {
            PriceLevelLines::where('ID', '=', $ID)
                ->update([
                    'CUSTOM_PRICE' => $CUSTOM_PRICE,
                ]);
            return;
        }
        PriceLevelLines::where('ID', '=', $ID)
            ->update([
                'CUSTOM_PRICE' => $CUSTOM_PRICE,
                'CUSTOM_COST'  => $CUSTOM_COST,
            ]);
    }
    public function GetByLocation(int $LOCATION_ID, int $ITEM_ID): array
    {
        $locDate = $this->locationServices->get($LOCATION_ID);
        if ($locDate) {
            $PRICE_LEVEL_ID = $locDate->PRICE_LEVEL_ID ?? 0;
            $result         = PriceLevelLines::select(['CUSTOM_COST', 'CUSTOM_PRICE'])
                ->where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                ->where('ITEM_ID', $ITEM_ID)
                ->first();

            if ($result) {
                return [
                    'COST'  => (float) $result->CUSTOM_COST ?? 0,
                    'PRICE' => (float) $result->CUSTOM_PRICE ?? 0,
                ];
            }
        }

        return [
            'COST'  => 0,
            'PRICE' => 0,
        ];
    }
    public function UpdateCostByItem(int $ID, int $ITEM_ID, float $CUSTOM_COST = 0): void
    {
        PriceLevelLines::where('ID', '=', $ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->update([
                'CUSTOM_COST' => $CUSTOM_COST,
            ]);
    }
    public function GetCostByLocation(int $LOCTION_ID, int $ITEM_ID): float
    {
        $locDate = $this->locationServices->get($LOCTION_ID);
        if ($locDate) {
            $PRICE_LEVEL_ID = $locDate->PRICE_LEVEL_ID ?? 0;
            $result         = PriceLevelLines::select(['CUSTOM_COST'])
                ->where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                ->where('ITEM_ID', $ITEM_ID)->first();
            if ($result) {
                return (float) $result->CUSTOM_COST ?? 0;
            }
        }

        return 0;
    }
    public function SetCostByLocation(int $LOCTION_ID, int $ITEM_ID, float $COST)
    {
        $locDate = $this->locationServices->get($LOCTION_ID);
        if ($locDate) {
            $PRICE_LEVEL_ID = $locDate->PRICE_LEVEL_ID ?? 0;
            if ($PRICE_LEVEL_ID > 0) {

                // Check first

                $exist = (bool) PriceLevelLines::where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                    ->where('ITEM_ID', '=', $ITEM_ID)
                    ->exists();

                if ($exist) {
                    PriceLevelLines::where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                        ->where('ITEM_ID', '=', $ITEM_ID)
                        ->update(['CUSTOM_COST' => $COST]);
                    return;
                }

                $this->Store(
                    $PRICE_LEVEL_ID,
                    $ITEM_ID,
                    0,
                    $COST
                );
            }
        }
    }
    public function GetPriceByLocation(int $LOCATION_ID, int $ITEM_ID): float
    {
        $locDate = $this->locationServices->get($LOCATION_ID);
        if ($locDate) {
            $PRICE_LEVEL_ID = $locDate->PRICE_LEVEL_ID ?? 0;
            if ($PRICE_LEVEL_ID > 0) {
                $PRICE_LEVEL_ID = $locDate->PRICE_LEVEL_ID ?? 0;
                $result         = PriceLevelLines::select(['CUSTOM_PRICE'])
                    ->where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                    ->where('ITEM_ID', '=', $ITEM_ID)
                    ->first();

                if ($result) {
                    return (float) $result->CUSTOM_PRICE ?? 0;
                }
            }
        }

        return 0;
    }

    public function SetPriceByLocation(int $LOCTION_ID, int $ITEM_ID, float $PRICE)
    {
        $locDate = $this->locationServices->get($LOCTION_ID);
        if ($locDate) {
            $PRICE_LEVEL_ID = $locDate->PRICE_LEVEL_ID ?? 0;

            if ($PRICE_LEVEL_ID > 0) {
                $exist = (bool) PriceLevelLines::where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                    ->where('ITEM_ID', '=', $ITEM_ID)
                    ->exists();

                if ($exist) {
                    PriceLevelLines::where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                        ->where('ITEM_ID', '=', $ITEM_ID)
                        ->update(['CUSTOM_PRICE' => $PRICE]);
                    return;
                }

                $this->Store(
                    $PRICE_LEVEL_ID,
                    $ITEM_ID,
                    $PRICE,
                    0
                );
            }
        }
    }
    public function UpdatePrice(int $ID, float $CUSTOM_PRICE = 0): void
    {
        PriceLevelLines::where('ID', '=', $ID)
            ->update([
                'CUSTOM_PRICE' => $CUSTOM_PRICE,
            ]);
    }
    public function Delete(int $ID): void
    {
        PriceLevelLines::where('ID', $ID)
            ->delete();
    }
    public function Remove(int $ITEM_ID, int $PRICE_LEVEL_ID)
    {
        PriceLevelLines::where('ITEM_ID', '=', $ITEM_ID)
            ->where('PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
            ->delete();
    }
    public function Search($search, int $PRICE_LEVEL_ID)
    {
        $result = PriceLevelLines::query()
            ->select([
                'price_level_lines.ID',
                'price_level_lines.PRICE_LEVEL_ID',
                'price_level_lines.ITEM_ID',
                'price_level_lines.CUSTOM_PRICE',
                'price_level_lines.CUSTOM_COST',
                'item.CODE',
                'item.DESCRIPTION',
                'item.RATE',
            ])
            ->join('item', 'item.ID', '=', 'price_level_lines.ITEM_ID')
            ->where('price_level_lines.PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('item.CODE', 'like', '%' . $search . '%')
                        ->orWhere('price_level_lines.CUSTOM_PRICE', 'like', '%' . $search . '%')
                        ->orWhere('price_level_lines.CUSTOM_COST', 'like', '%' . $search . '%')
                        ->orWhere('item.DESCRIPTION', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('price_level_lines.ID', 'desc')
            ->get();

        return $result;
    }

    public function LoadPriceLevelByItem(int $itemId)
    {
        $result = PriceLevelLines::query()
            ->select(
                [
                    'price_level_lines.ID',
                    'price_level.DESCRIPTION',
                    'price_level_lines.CUSTOM_PRICE',
                    'price_level_lines.CUSTOM_COST',
                ]
            )
            ->join('price_level', 'price_level.ID', '=', 'price_level_lines.PRICE_LEVEL_ID')
            ->where('price_level_lines.ITEM_ID', '=', $itemId)
            ->where('price_level.INACTIVE', '=', '0')
            ->where('price_level.TYPE', '=', '1')
            ->get();

        return $result;
    }

    public function DataExists(int $ITEM_ID, int $LOCATION_ID)
    {

        $data = PriceLevelLines::query()
            ->select('price_level_lines.ID')
            ->join('price_level', 'price_level.ID', '=', 'price_level_lines.PRICE_LEVEL_ID')
            ->join('LOCATION as l', 'l.PRICE_LEVEL_ID', '=', 'price_level.ID')
            ->where('price_level_lines.ITEM_ID', '=', $ITEM_ID)
            ->where('l.ID', '=', $LOCATION_ID)
            ->first();

        if ($data) {
            return (int) $data->ID;
        }

        return 0;
    }
    public function PriceExists(int $ITEM_ID, int $LOCATION_ID): array
    {

        $data = PriceLevelLines::query()
            ->select([
                'price_level_lines.CUSTOM_PRICE',
                'price_level_lines.CUSTOM_COST',
            ])
            ->join('price_level', 'price_level.ID', '=', 'price_level_lines.PRICE_LEVEL_ID')
            ->join('LOCATION as l', 'l.PRICE_LEVEL_ID', '=', 'price_level.ID')
            ->where('price_level_lines.ITEM_ID', '=', $ITEM_ID)
            ->where('l.ID', '=', $LOCATION_ID)
            ->first();

        if ($data) {

            return [
                'PRICE' => $data->CUSTOM_PRICE ?? 0,
                'COST'  => $data->CUSTOM_COST ?? 0,
            ];
        }

        return [
            'PRICE' => 0,
            'COST'  => 0,
        ];
    }
}
