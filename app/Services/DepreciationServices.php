<?php
namespace App\Services;

use App\Models\Depreciation;
use App\Models\DepreciationItems;
use App\Models\Locations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepreciationServices
{
    public int $object_type_depreciation      = 127;
    public int $object_type_depreciation_item = 128;
    public int $DEPRECIATION_ACCOUNT_ID       = 268;
    public int $ACCUMULATED_ACCOUNT_ID        = 19;
    private $object;
    private $systemSettingServices;
    private $dateServices;
    private $fixedAssetItemServices;
    private $accountJournalServices;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices, FixedAssetItemServices $fixedAssetItemServices, AccountJournalServices $accountJournalServices)
    {
        $this->object                 = $objectServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->dateServices           = $dateServices;
        $this->fixedAssetItemServices = $fixedAssetItemServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function monthlyExecute(): string
    {

        // get the requirements
        $isTrue = $this->dateServices->isFirstDayOfMonth();
        // !  true or false
        if ($isTrue) {
            DB::beginTransaction();
            try {
                $locationList = Locations::where('INACTIVE', '=', false)->get();
                foreach ($locationList as $list) {
                    $this->AutoMakeDeprecation($list->ID);
                }
                DB::commit();
                return "success";
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error('Error executing Depreciation : ' . $th->getMessage());
                return "failed: " . $th->getMessage();
            }

        }
        Log::error('Error is First Day of Month is fales');
        return "failed: dayIsNextMonth is false";
    }
    private function AutoMakeDeprecation(int $location_id)
    {

        // check if already added this month.
        $count = $this->fixedAssetItemServices->GetCount($location_id);
        if ($count > 0) {
            if (! $this->IsExist($location_id)) {
                $DEPRECATION_ID = (int) $this->Store('', $this->dateServices->NowDate(), $location_id, $this->DEPRECIATION_ACCOUNT_ID, '', true);
                $this->AutoMakeItem($DEPRECATION_ID, $location_id);                                // add item automatic
                $this->Recomputed($DEPRECATION_ID);                                                // update total
                $this->autoJournal($DEPRECATION_ID, $location_id, $this->dateServices->NowDate()); // create journal
                $this->StatusUpdate($DEPRECATION_ID, 15);                                          // make posted
                return;
            }
            Log::error('Error : file already exists ');
            return;
        }
    }
    private function AutoMakeItem(int $DEP_ID, int $LOC_ID)
    {
        $allowedAllIn = true;
        $data         = $this->fixedAssetItemServices->List($LOC_ID);
        foreach ($data as $list) {
            $RM = (int) $list->REMAINING_MONTHS;
            if ($RM >= 0 || $allowedAllIn) {
                $AMT       = (float) $list->AQ_COST / $list->USEFUL_LIFE;
                $PER_MONTH = $AMT / 12;
                $this->ItemStore(
                    $DEP_ID,
                    $list->ID,
                    $PER_MONTH,
                    $list->ACCUMULATED_ACCOUNT_ID ?? 0
                );
                // checking if max
                $itemCount = (int) $this->GetCount($list->ID, $LOC_ID);
                if ($itemCount >= $list->USEFUL_LIFE) {
                    $this->fixedAssetItemServices->AutoInactive($list->ID);
                }
            }
        }
    }
    private function autoJournal(int $ID, int $LOCATION_ID, string $DATE)
    {
        $JOURNAL_NO       = (int) $this->accountJournalServices->getJournalNo($this->object_type_depreciation, $ID) + 1;
        $depreciationData = $this->DepreciationJournal($ID);
        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $depreciationData,
            $LOCATION_ID,
            $this->object_type_depreciation,
            $DATE,
            ""
        );

        $depreciationItemData = $this->DepreciationItemJournal($ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $depreciationItemData,
            $LOCATION_ID,
            $this->object_type_depreciation_item,
            $DATE,
            "ASSET"
        );
    }
    private function GetCount(int $FIXED_ASSET_ITEM_ID, $LOC_ID): int
    {

        // ACTUAL_INPUT
        $itemCount = (int) DepreciationItems::query()->join('depreciation as d', 'd.ID', '=', 'DEPRECIATION_ID')
            ->where('d.LOCATION_ID', '=', $LOC_ID)
            ->where('FIXED_ASSET_ITEM_ID', '=', $FIXED_ASSET_ITEM_ID)
            ->count();

        return $itemCount;
    }
    private function IsExist(int $location_id): bool
    {
        return (bool) Depreciation::where('DATE', '=', $this->dateServices->NowDate())
            ->where('LOCATION_ID', '=', $location_id)
            ->exists();
    }

    public function Get($ID)
    {
        $data = Depreciation::where('ID', '=', $ID)->first();

        if ($data) {
            return $data;
        }
        return null;
    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID, int $DEPRECIATION_ACCOUNT_ID, string $NOTES, bool $IS_AUTO): int
    {
        $ID          = $this->object->ObjectNextID('DEPRECIATION');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('DEPRECIATION');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Depreciation::create(
            [
                'ID'                      => $ID,
                'RECORDED_ON'             => $this->dateServices->Now(),
                'CODE'                    => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
                'DATE'                    => $DATE,
                'LOCATION_ID'             => $LOCATION_ID,
                'DEPRECIATION_ACCOUNT_ID' => $DEPRECIATION_ACCOUNT_ID > 0 ? $DEPRECIATION_ACCOUNT_ID : null,
                'NOTES'                   => $NOTES,
                'IS_AUTO'                 => $IS_AUTO,
                'AMOUNT'                  => 0,
                'STATUS'                  => 0,
                'STATUS_DATE'             => $this->dateServices->NowDate(),
            ]
        );

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Depreciation::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);
    }

    public function Recomputed(int $ID)
    {
        $AMOUNT = (float) DepreciationItems::where('DEPRECIATION_ID', '=', $ID)->sum('AMOUNT');

        Depreciation::where('ID', '=', $ID)->update(['AMOUNT' => $AMOUNT]);
    }
    public function Update(int $ID, string $CODE, int $DEPRECIATION_ACCOUNT_ID, string $NOTES)
    {

        Depreciation::where('ID', '=', $ID)
            ->update([
                'CODE'                    => $CODE,
                'DEPRECIATION_ACCOUNT_ID' => $DEPRECIATION_ACCOUNT_ID > 0 ? $DEPRECIATION_ACCOUNT_ID : null,
                'NOTES'                   => $NOTES,
            ]);
    }
    public function Delete(int $ID)
    {
        DepreciationItems::where('DEPRECIATION_ID', '=', $ID)->delete();
        Depreciation::where('ID', '=', $ID)->delete();
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = Depreciation::query()
            ->select([
                'depreciation.ID',
                'depreciation.CODE',
                'depreciation.DATE',
                'l.NAME as LOCATION_NAME',
                'a.NAME as ACCOUNT_NAME',
                's.DESCRIPTION as STATUS',
                'depreciation.AMOUNT',
            ])
            ->join('location as l', 'l.ID', '=', 'depreciation.LOCATION_ID')
            ->join('account as a', 'a.ID', '=', 'depreciation.DEPRECIATION_ACCOUNT_ID')
            ->join('document_status_map as s', 's.ID', '=', 'depreciation.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->orWhere('depreciation.CODE', 'like', "%$search%");
                });
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('depreciation.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->orderBy('depreciation.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function ItemGet(int $ID)
    {
        $data = DepreciationItems::where('ID', '=', $ID)->first();

        return $data;
    }
    public function ItemExistsAdded(int $DEPRECIATION_ID, int $FIXED_ASSET_ITEM_ID): bool
    {
        return (bool) DepreciationItems::where('DEPRECIATION_ID', '=', $DEPRECIATION_ID)->where('FIXED_ASSET_ITEM_ID', '=', $FIXED_ASSET_ITEM_ID)->exists();
    }
    public function ItemStore(int $DEPRECIATION_ID, int $FIXED_ASSET_ITEM_ID, float $AMOUNT, int $ACCOUNT_ID)
    {
        $ID = (int) $this->object->ObjectNextID('DEPRECIATION_ITEMS');

        DepreciationItems::create([
            'ID'                  => $ID,
            'DEPRECIATION_ID'     => $DEPRECIATION_ID,
            'FIXED_ASSET_ITEM_ID' => $FIXED_ASSET_ITEM_ID,
            'AMOUNT'              => $AMOUNT,
            'ACCOUNT_ID'          => $ACCOUNT_ID,
        ]);

        return $ID;
    }
    public function ItemUpdate(int $ID, float $AMOUNT)
    {
        DepreciationItems::where('ID', '=', $ID)
            ->update([
                'ID'     => $ID,
                'AMOUNT' => $AMOUNT,
            ]);
    }
    public function ItemDelete(int $ID)
    {
        DepreciationItems::where('ID', '=', $ID)->delete();
    }
    public function ItemList(int $DEPRECIATION_ID)
    {
        $result = DepreciationItems::query()
            ->select([
                'depreciation_items.ID',
                'depreciation_items.DEPRECIATION_ID',
                'depreciation_items.FIXED_ASSET_ITEM_ID',
                'depreciation_items.ACCOUNT_ID',
                'depreciation_items.AMOUNT',
                'i.DESCRIPTION as ITEM_NAME',
                'f.ID as ASSET_ITEM_ID',
                'a.NAME as ACCOUNT_NAME',

            ])
            ->join('fixed_asset_item as f', 'f.ID', '=', 'depreciation_items.FIXED_ASSET_ITEM_ID')
            ->join('item as i', 'i.ID', '=', 'f.ITEM_ID')
            ->join('account as a', 'a.ID', 'depreciation_items.ACCOUNT_ID')
            ->where('depreciation_items.DEPRECIATION_ID', '=', $DEPRECIATION_ID)
            ->get();

        return $result;
    }
    public function DepreciationJournal(int $DEPRECIATION_ID)
    {

        $result = Depreciation::query()
            ->select([
                'ID',
                'DEPRECIATION_ACCOUNT_ID as ACCOUNT_ID',
                DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $DEPRECIATION_ID)
            ->get();

        return $result;
    }
    public function DepreciationItemJournal(int $DEPRECIATION_ID)
    {
        $result = DepreciationItems::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'FIXED_ASSET_ITEM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('DEPRECIATION_ID', '=', $DEPRECIATION_ID)
            ->get();

        return $result;
    }
    public function IsFixedAssetAlreadyDepreciation(int $FIXED_ASSET_ITEM_ID): bool
    {
        return DepreciationItems::query()
            ->where('FIXED_ASSET_ITEM_ID', '=', $FIXED_ASSET_ITEM_ID)
            ->exists();
    }

    public function ShowAssetHistory(int $FIXED_ASSET_ITEM_ID)
    {
        return DepreciationItems::query()
            ->select([
                'd.ID',
                'd.CODE',
                'd.DATE',
                's.DESCRIPTION as STATUS',
                'depreciation_items.AMOUNT',
            ])
            ->join('depreciation as d', 'd.ID', '=', 'depreciation_items.DEPRECIATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'd.STATUS')
            ->where('depreciation_items.FIXED_ASSET_ITEM_ID', '=', $FIXED_ASSET_ITEM_ID)
            ->orderBy('d.DATE', 'asc')
            ->get();
    }
}
