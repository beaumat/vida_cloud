<?php
namespace App\Services;

use App\Models\FixedAssetItem;
use Illuminate\Support\Facades\DB;

class FixedAssetItemServices
{
    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function Get(int $ID)
    {
        $result = FixedAssetItem::where('ID', '=', $ID)->first();
        return $result;
    }
    public function Store(
        int $ITEM_ID,
        int $LOCATION_ID,
        int $ACCUMULATED_ACCOUNT_ID,
        int $DEPRECIATION_ACCOUNT_ID,
        string $PO_NUMBER,
        string $SERIAL_NO,
        string $WARRANTIY_EXPIRED,
        bool $PERSONAL_PROPERTY_RETURN,
        bool $IS_NEW,
        string $OTHER_DESCRIPTION,
        int $YEAR_PURCHASE,
        int $YEAR_MODEL,
        int $QUANTITY,
        float $AQ_COST,
        int $USEFUL_LIFE,
        string $PO_DATE
    ) {

        $ID = (int) $this->object->ObjectNextID('FIXED_ASSET_ITEM');
        FixedAssetItem::create([
            'ID'                       => $ID,
            'ITEM_ID'                  => $ITEM_ID,
            'LOCATION_ID'              => $LOCATION_ID,
            'ACCUMULATED_ACCOUNT_ID'   => $ACCUMULATED_ACCOUNT_ID > 0 ? $ACCUMULATED_ACCOUNT_ID : null,
            'DEPRECIATION_ACCOUNT_ID'  => $DEPRECIATION_ACCOUNT_ID > 0 ? $DEPRECIATION_ACCOUNT_ID : null,
            'PO_NUMBER'                => $PO_NUMBER,
            'SERIAL_NO'                => $SERIAL_NO,
            'WARRANTIY_EXPIRED'        => $WARRANTIY_EXPIRED == '' ? null : $WARRANTIY_EXPIRED,
            'PERSONAL_PROPERTY_RETURN' => $PERSONAL_PROPERTY_RETURN,
            'IS_NEW'                   => $IS_NEW,
            'OTHER_DESCRIPTION'        => $OTHER_DESCRIPTION,
            'YEAR_PURCHASE'            => $YEAR_PURCHASE,
            'YEAR_MODEL'               => $YEAR_MODEL,
            'QUANTITY'                 => $QUANTITY,
            'AQ_COST'                  => $AQ_COST,
            'USEFUL_LIFE'              => $USEFUL_LIFE,
            'PO_DATE'                  => $PO_DATE,
        ]);
    }
    public function Update(
        int $ID,
        int $ACCUMULATED_ACCOUNT_ID,
        int $DEPRECIATION_ACCOUNT_ID,
        string $PO_NUMBER,
        string $SERIAL_NO,
        string $WARRANTIY_EXPIRED,
        bool $PERSONAL_PROPERTY_RETURN,
        bool $IS_NEW,
        string $OTHER_DESCRIPTION,
        int $YEAR_PURCHASE,
        int $YEAR_MODEL,
        int $QUANTITY,
        float $AQ_COST,
        int $USEFUL_LIFE,
        bool $INACTIVE,
        string $PO_DATE
    ) {
        FixedAssetItem::where('ID', '=', $ID)
            ->update([
                'ACCUMULATED_ACCOUNT_ID'   => $ACCUMULATED_ACCOUNT_ID > 0 ? $ACCUMULATED_ACCOUNT_ID : null,
                'DEPRECIATION_ACCOUNT_ID'  => $DEPRECIATION_ACCOUNT_ID > 0 ? $DEPRECIATION_ACCOUNT_ID : null,
                'PO_NUMBER'                => $PO_NUMBER,
                'SERIAL_NO'                => $SERIAL_NO,
                'WARRANTIY_EXPIRED'        => $WARRANTIY_EXPIRED == '' ? null : $WARRANTIY_EXPIRED,
                'PERSONAL_PROPERTY_RETURN' => $PERSONAL_PROPERTY_RETURN,
                'IS_NEW'                   => $IS_NEW,
                'OTHER_DESCRIPTION'        => $OTHER_DESCRIPTION,
                'YEAR_PURCHASE'            => $YEAR_PURCHASE,
                'YEAR_MODEL'               => $YEAR_MODEL,
                'QUANTITY'                 => $QUANTITY,
                'AQ_COST'                  => $AQ_COST,
                'USEFUL_LIFE'              => $USEFUL_LIFE,
                'INACTIVE'                 => $INACTIVE,
                'PO_DATE'                  => $PO_DATE,
            ]);
    }

    public function Delete(int $ID)
    {
        FixedAssetItem::where('ID', '=', $ID)->delete();
    }
    public function getList(int $LOCATION_ID)
    {
        $result = FixedAssetItem::query()
            ->select([
                'fixed_asset_item.ID',
                DB::raw("concat(i.DESCRIPTION ,' (#', fixed_asset_item.ID,')' ) as DESCRIPTION"),
            ])
            ->join('item as i', 'i.ID', '=', 'fixed_asset_item.ITEM_ID')
            ->where("fixed_asset_item.LOCATION_ID", '=', $LOCATION_ID)
            ->where("fixed_asset_item.INACTIVE", '=', false)
            ->get();

        return $result;
    }

    public function list(int $LOCATION_ID)
    {
        $result = FixedAssetItem::query()
            ->select([
                'ID',
                'ACCUMULATED_ACCOUNT_ID',
                'AQ_COST',
                'USEFUL_LIFE',
                DB::raw("TIMESTAMPDIFF(MONTH,CURDATE(),DATE_ADD(PO_DATE, INTERVAL USEFUL_LIFE YEAR)) as REMAINING_MONTHS"),
            ])
            ->where("LOCATION_ID", '=', $LOCATION_ID)
            ->where("INACTIVE", '=', false)
            ->get();

        return $result;
    }
    public function AutoInactive(int $ID)
    {
        FixedAssetItem::where("ID", '=', $ID)
            ->where("INACTIVE", '=', false)
            ->update([
                'INACTIVE' => true,
            ]);
    }
    public function GetCount(int $LOCATION_ID): int
    {
        $result = (int) FixedAssetItem::where("LOCATION_ID", '=', $LOCATION_ID)
            ->where("INACTIVE", '=', false)
            ->count();

        return $result;
    }

    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = FixedAssetItem::query()
            ->select([
                'fixed_asset_item.ID',
                'fixed_asset_item.PO_NUMBER',
                'fixed_asset_item.SERIAL_NO',
                'fixed_asset_item.YEAR_PURCHASE',
                'fixed_asset_item.PO_DATE',
                'fixed_asset_item.YEAR_MODEL',
                'fixed_asset_item.QUANTITY',
                'fixed_asset_item.AQ_COST',
                'fixed_asset_item.USEFUL_LIFE',
                'fixed_asset_item.INACTIVE',
                'a.NAME as ASSET_ACCOUNT',
                'aa.NAME as ACCUMULATED_ACCOUNT',
                'da.NAME as DEPRECIATION_ACCOUNT',
                'i.DESCRIPTION as ITEM_NAME',
                'i.CODE as ITEM_CODE',
                'u.NAME as UNIT_NAME',
                'l.NAME as LOCATION_NAME',
                DB::raw('(fixed_asset_item.AQ_COST/fixed_asset_item.USEFUL_LIFE) as PER_YEAR'),
                DB::raw('((fixed_asset_item.AQ_COST/fixed_asset_item.USEFUL_LIFE)/12) as PER_MONTH'),
                DB::raw('(SELECT COUNT(*) FROM depreciation_items AS di INNER JOIN depreciation AS d ON d.ID = di.DEPRECIATION_ID  WHERE di.FIXED_ASSET_ITEM_ID = fixed_asset_item.ID AND d.STATUS = 15) as DEPRECIATION_COUNT '),
                DB::raw('(fixed_asset_item.USEFUL_LIFE * 12 ) as DEPRECIATION_UNTIL'),
                DB::raw("TIMESTAMPDIFF(MONTH,CURDATE(),DATE_ADD(PO_DATE, INTERVAL USEFUL_LIFE YEAR)) as REMAINING_MONTHS"),
            ])
            ->join('item as i', 'i.ID', '=', 'fixed_asset_item.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'i.BASE_UNIT_ID')
            ->leftJoin('account as a', 'a.ID', '=', 'i.ASSET_ACCOUNT_ID')
            ->leftJoin('account as aa', 'aa.ID', '=', 'fixed_asset_item.ACCUMULATED_ACCOUNT_ID')
            ->leftJoin('account as da', 'da.ID', '=', 'fixed_asset_item.DEPRECIATION_ACCOUNT_ID')
            ->leftJoin('location as l', 'l.ID', '=', 'fixed_asset_item.LOCATION_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where("fixed_asset_item.LOCATION_ID", '=', $LOCATION_ID);
            })
            ->where(function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->orWhere('i.DESCRIPTION', 'like', "%$search%")
                        ->orWhere('fixed_asset_item.OTHER_DESCRIPTION', 'like', "%$search%")
                        ->orWhere('fixed_asset_item.PO_NUMBER', 'like', "%$search%")
                        ->orWhere('fixed_asset_item.SERIAL_NO', 'like', "%$search%")
                        ->orWhere('a.NAME', 'like', "%$search%")
                        ->orWhere('aa.NAME', 'like', "%$search%")
                        ->orWhere('da.NAME', 'like', "%$search%")
                    ;
                });
            })
            ->orderBy('fixed_asset_item.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
}
