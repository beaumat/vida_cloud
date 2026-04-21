<?php
namespace App\Services;

use App\Models\ItemSoa;
use App\Models\ItemSoaType;
use Illuminate\Support\Facades\DB;

class ItemSoaServices
{

    private $object;
    private $itemSoaItemizedServices;
    public function __construct(ObjectServices $objectServices, ItemSoaItemizedServices $itemSoaItemizedServices)
    {
        $this->object = $objectServices;
        $this->itemSoaItemizedServices = $itemSoaItemizedServices;
    }

    public function TypeList()
    {

        $result = ItemSoaType::get();

        return $result;
    }
    public function Get(int $ID)
    {
        $result = ItemSoa::where('ID', '=', $ID)->first();

        return $result;
    }
    private function NextLine(int $TYPE, int $LOCATION_ID)
    {
        return (int) ItemSoa::where('TYPE', '=', $TYPE)->where('LOCATION_ID', '=', $LOCATION_ID)->max('LINE') + 1;
    }
    public function Store(int $LOCATION_ID, int $TYPE, int $LINE, string $ITEM_NAME, string $UNIT_NAME, float $RATE, bool $ACTUAL_BASE = false, string $DOSAGE, string $ROUTE, string $FREQUENCY, string $BRAND, int $GROUP_ID, bool $SC_BASE, bool $SOA_BASE, string $GENERIC_NAME)
    {
        $ID = $this->object->ObjectNextID('SOA_ITEM');

        ItemSoa::create(
            [
                'ID' => $ID,
                'LOCATION_ID' => $LOCATION_ID,
                'LINE' => $LINE > 0 ? $LINE : $this->NextLine($TYPE, $LOCATION_ID),
                'TYPE' => $TYPE,
                'ITEM_NAME' => $ITEM_NAME,
                'UNIT_NAME' => $UNIT_NAME,
                'RATE' => $RATE,
                'ACTUAL_BASE' => $ACTUAL_BASE,
                'DOSAGE' => $DOSAGE,
                'ROUTE' => $ROUTE,
                'FREQUENCY' => $FREQUENCY,
                'BRAND' => $BRAND,
                'GROUP_ID' => $GROUP_ID > 0 ? $GROUP_ID : null,
                'SC_BASE' => $SC_BASE,
                'SOA_BASE' => $SOA_BASE,
                'GENERIC_NAME' => $GENERIC_NAME,
            ]
        );

        return $ID;
    }
    public function Update(int $ID, int $TYPE, int $LINE, string $ITEM_NAME, string $UNIT_NAME, float $RATE, bool $ACTUAL_BASE = false, string $DOSAGE, string $ROUTE, string $FREQUENCY, string $BRAND, int $GROUP_ID, bool $SC_BASE, bool $SOA_BASE, string $GENERIC_NAME, int $FIX_QTY = 0, bool $ITEM_CONTROL_A, bool $ITEM_CONTROL_B, bool $ITEM_HIDE)
    {
        ItemSoa::where('ID', '=', $ID)
            ->update(
                [
                    'ID' => $ID,
                    'TYPE' => $TYPE,
                    'LINE' => $LINE,
                    'ITEM_NAME' => $ITEM_NAME,
                    'UNIT_NAME' => $UNIT_NAME,
                    'RATE' => $RATE,
                    'ACTUAL_BASE' => $ACTUAL_BASE,
                    'DOSAGE' => $DOSAGE,
                    'ROUTE' => $ROUTE,
                    'FREQUENCY' => $FREQUENCY,
                    'BRAND' => $BRAND,
                    'GROUP_ID' => $GROUP_ID > 0 ? $GROUP_ID : null,
                    'SC_BASE' => $SC_BASE,
                    'SOA_BASE' => $SOA_BASE,
                    'GENERIC_NAME' => $GENERIC_NAME,
                    'FIX_QTY' => $FIX_QTY,
                    'ITEM_CONTROL_A' => $ITEM_CONTROL_A,
                    'ITEM_CONTROL_B' => $ITEM_CONTROL_B,
                    'ITEM_HIDE' => $ITEM_HIDE
                ]
            );
    }
    public function Delete(int $ID)
    {
        ItemSoa::where('ID', '=', $ID)
            ->delete();
    }
    public function UpdateInactive(int $ID, bool $INACTIVE)
    {
        ItemSoa::where('ID', '=', $ID)
            ->update(['INACTIVE' => $INACTIVE]);
    }

    public function Search($search, int $LOCATION_ID): object
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.LINE',
                'soa_item.DOSAGE',
                'soa_item.ROUTE',
                'soa_item.FREQUENCY',
                'soa_item.BRAND',
                'soa_item.INACTIVE',
                'soa_item.GROUP_ID',
                'soa_item.SC_BASE',
                'soa_item.SOA_BASE',
                'soa_item.GENERIC_NAME',
                'soa_item.FIX_QTY',
                'soa_item.ITEM_CONTROL_A',
                'soa_item.ITEM_CONTROL_B',
                'soa_item.ITEM_HIDE'
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->orWhere('soa_item_type.DESCRIPTION', 'like', "%$search%")
                        ->orWhere('ITEM_NAME', 'like', "%$search%")
                        ->orWhere('UNIT_NAME', 'like', "%$search%");
                });
            })
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;
    }

    public function GetList(int $LOCATION_ID)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.GROUP_ID',
                'soa_item.FIX_QTY',
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;
    }
    public function GetListLoop(int $LOCATION_ID, array $breakDownDate = [], int $contactId)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.GROUP_ID',
                'soa_item.FIX_QTY',
                'soa_item.SC_BASE',
                'soa_item.ITEM_CONTROL_A',
                'soa_item.ITEM_CONTROL_B',
                'soa_item.ITEM_HIDE'
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('soa_item.LOCATION_ID', '=', $LOCATION_ID)
            ->where('soa_item.INACTIVE', '=', false)
            ->where('soa_item.ITEM_HIDE', '=', false)
            ->orderBy('soa_item.TYPE', 'asc')
            ->orderBy('soa_item.LINE', 'asc')
            ->get();



        $dataList = [];

        // If no breakDownDate is provided, use the current date
        foreach ($breakDownDate as $myDate) {
            $TMP_ADD = 0;
            // If no date is provided, use the current date
            $TMP_RATE = 0;
            foreach ($result as $item) {
                $gotQty = 1;
                $TMP_RATE = $item->RATE;
                // If the item has a fixed quantity, repeat it for the specified number of times
                if ($item->ITEM_CONTROL_A == true) {
                    if ($this->itemSoaItemizedServices->isExistThatDay($myDate, $LOCATION_ID, $contactId, $item->ID)) {
                        $gotQty = 1;
                        $TMP_ADD = 0;
                    } else {
                        $gotQty = 0;
                        $TMP_ADD = $item->RATE;
                    }
                }

                if ($item->ITEM_CONTROL_B == true) {
                    if ($TMP_ADD > 0) {
                        $TMP_RATE = $item->RATE + $TMP_ADD;
                        $TMP_ADD = 0;
                    } else {

                        $TMP_RATE = $item->RATE;
                    }

                }

                $dataList[] = [
                    'DATE' => $myDate,
                    'ID' => $item->ID,
                    'TYPE' => $item->TYPE,
                    'TYPE_NAME' => $item->TYPE_NAME,
                    'ITEM_NAME' => $item->ITEM_NAME,
                    'UNIT_NAME' => $item->UNIT_NAME,
                    'RATE' => $TMP_RATE,
                    'ACTUAL_BASE' => $item->ACTUAL_BASE,
                    'GROUP_ID' => $item->GROUP_ID,
                    'FIX_QTY' => $item->FIX_QTY,
                    'QTY' => $gotQty,
                ];
            }
        }

        return $dataList;
    }

    public function GetListViaType(int $LOCATION_ID, int $TYPE)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.GROUP_ID',
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('TYPE', '=', $TYPE)
            ->where('INACTIVE', '=', false)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;
    }
    public function GetListTypeFixedQty(int $LOCATION_ID, int $TYPE, int $LOOP = 1)
    {
        $QTY = 1;

        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.ACTUAL_BASE',
                'soa_item.DOSAGE',
                'soa_item.ROUTE',
                'soa_item.FREQUENCY',
                'soa_item.BRAND',
                'soa_item.SC_BASE',
                DB::raw("($QTY * soa_item.RATE) as RATE"),
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->where('TYPE', '=', $TYPE)
            ->where('SOA_BASE', '=', true)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        // Repeat each entry based on $LOOP
        $result = $result->flatMap(function ($item) use ($LOOP) {
            return collect(array_fill(0, $LOOP, $item));
        });

        return $result->values(); // Reindex the collection
    }
    public static function getTotal(int $GROUP_ID, int $LOCATION_ID): float
    {
        return (float) ItemSoa::where('soa_item.GROUP_ID', '=', $GROUP_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->sum('RATE');
    }
    public function HaveServiceChargeBase(int $LOCATION_ID): bool
    {
        return (bool) ItemSoa::where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->where('TYPE', '=', 1)
            ->where('SC_BASE', '=', true)
            ->exists();
    }
    public function HaveSOABase(int $LOCATION_ID): bool
    {
        return (bool) ItemSoa::where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->where('TYPE', '=', 1)
            ->where('SOA_BASE', '=', true)
            ->exists();
    }
    public function GetMedicineListBySCBase(int $LOCATION_ID)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.DOSAGE',
                'soa_item.ROUTE',
                'soa_item.FREQUENCY',
                'soa_item.BRAND',
                'soa_item.SC_BASE',
                'soa_item.GENERIC_NAME',

            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('TYPE', '=', 1)
            ->where('SC_BASE', '=', true)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;

    }

    public function GetMedicineListBySOA_Base(int $LOCATION_ID)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.DOSAGE',
                'soa_item.ROUTE',
                'soa_item.FREQUENCY',
                'soa_item.BRAND',
                'soa_item.SC_BASE',
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->where('TYPE', '=', 1)
            ->where('SOA_BASE', '=', true)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;

    }
    public function GetMedicineList(int $LOCATION_ID)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.DOSAGE',
                'soa_item.ROUTE',
                'soa_item.FREQUENCY',
                'soa_item.BRAND',
                'soa_item.SC_BASE',

            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->where('TYPE', '=', 1)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;

    }
    public function getItemByCategory(int $LOCATION_ID, int $TYPE)
    {
        $result = ItemSoa::where('TYPE', '=', $TYPE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->get();

        return $result;
    }
    public function getItemBySingleCategoryWithSum(int $LOCATION_ID, int $TYPE)
    {
        $result = ItemSoa::where('TYPE', '=', $TYPE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->sum('RATE');

        if ($result > 0) {
            return (float) $result;
        }
        return 0.00;
    }
    public function getSumNonActualByType(int $TYPE, int $LOCATION_ID): float
    {
        $result = ItemSoa::where('TYPE', '=', $TYPE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('ACTUAL_BASE', '=', false)
            ->where('INACTIVE', '=', false)
            ->sum('RATE');

        if ($result > 0) {
            return (float) $result;
        }
        return 0.00;
    }

    public function haveDataExist(int $LOC_ID): bool
    {
        return ItemSoa::where('LOCATION_ID', '=', $LOC_ID)->exists();
    }
    public function copyEntryToAnotherLocation(int $FORM_LOCATION_ID, int $TO_LOCATION_ID)
    {

        $fromDataList = ItemSoa::where('LOCATION_ID', '=', $FORM_LOCATION_ID)->get();

        foreach ($fromDataList as $list) {
            $NEW_ID = $this->Store(
                $TO_LOCATION_ID,
                $list->TYPE,
                $list->LINE,
                $list->ITEM_NAME,
                $list->UNIT_NAME,
                $list->RATE,
                $list->ACTUAL_BASE,
                $list->DOSAGE ?? '',
                $list->ROUTE ?? '',
                $list->FREQUENCY ?? '',
                $list->BRAND ??     '',
                $list->GROUP_ID > 0 ? $list->GROUP_ID : 0,
                $list->SC_BASE ?? false,
                $list->SOA_BASE ?? false,
                $list->GENERIC_NAME ?? ''
            );

            if ($list->ACTUAL_BASE) {
                $dataItem = $this->itemSoaItemizedServices->GetList($list->ID);
                foreach ($dataItem as $itemList) {
                    $this->itemSoaItemizedServices->Store($itemList->ITEM_ID, $NEW_ID);
                }
            }
        }

    }

}