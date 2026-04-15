<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\BuildAssembly;
use App\Models\BuildAssemblyItems;
use App\Models\ItemComponents;
use App\Models\ItemKits;
use Illuminate\Support\Facades\DB;

class BuildAssemblyServices
{

    public int $object_type_build_assembly       = 70;
    public int $object_type_build_assembly_items = 71;
    public int $document_type_id                 = 19;
    private $object;
    private $systemSettingServices;
    private $dateServices;
    private $locationServices;
    private $itemServices;
    private $UsersLogServices;
    public function __construct(
        ObjectServices $objectService,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        ItemServices $itemServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                = $objectService;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->locationServices      = $locationServices;
        $this->itemServices          = $itemServices;
        $this->UsersLogServices      = $usersLogServices;
    }
    public function Get(int $ID)
    {
        return BuildAssembly::where('ID', $ID)->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $LOCATION_ID,
        int $ASSEMBLY_ITEM_ID,
        float $QUANTITY,
        int $BATCH_ID,
        int $UNIT_ID,
        int $UNIT_BASE_QUANTITY,
        string $NOTES,
        int $ASSET_ACCOUNT_ID
    ): int {

        $ID          = (int) $this->object->ObjectNextID('BUILD_ASSEMBLY');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('BUILD_ASSEMBLY');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        BuildAssembly::create([
            'ID'                 => $ID,
            'RECORDED_ON'        => $this->dateServices->Now(),
            'CODE'               => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'               => $DATE,
            'LOCATION_ID'        => $LOCATION_ID,
            'ASSEMBLY_ITEM_ID'   => $ASSEMBLY_ITEM_ID,
            'QUANTITY'           => $QUANTITY,
            'AMOUNT'             => 0,
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'NOTES'              => $NOTES,
            'ASSET_ACCOUNT_ID'   => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'STATUS'             => 0,
        ]);

        $newAmount = (float) $this->AutoCreateComponent(
            $ASSEMBLY_ITEM_ID,
            $ID,
            $QUANTITY,
            $LOCATION_ID
        );

        BuildAssembly::where('ID', $ID)->update(['AMOUNT' => $newAmount]);

        $this->UsersLogServices->AddLogs(TransType::INSERT, LogEntity::BUILD_ASSEMBLY, $ID);

        return (int) $ID;
    }

    public function Update(int $ID, string $CODE, int $ASSEMBLY_ITEM_ID, float $QUANTITY, int $BATCH_ID, int $UNIT_ID, int $UNIT_BASE_QUANTITY, string $NOTES, int $LOCATION_ID): float
    {
        BuildAssembly::where('ID', $ID)
            ->where('ASSEMBLY_ITEM_ID', $ASSEMBLY_ITEM_ID)
            ->update([
                'CODE'               => $CODE,
                'QUANTITY'           => $QUANTITY,
                'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
                'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'NOTES'              => $NOTES,
            ]);

        $newAmount = (float) $this->AutoUpdateComponent(
            $ASSEMBLY_ITEM_ID,
            $ID,
            $QUANTITY,
            $LOCATION_ID
        );
        
        BuildAssembly::where('ID', $ID)->update(['AMOUNT' => $newAmount]);

        $this->UsersLogServices->AddLogs(TransType::UPDATE, LogEntity::BUILD_ASSEMBLY, $ID);

        return $newAmount;
    }
    public function Delete(int $ID)
    {
        BuildAssemblyItems::where('BUILD_ASSEMBLY_ID', '=', $ID)->delete();
        BuildAssembly::where('ID', '=', $ID)->delete();

        $this->UsersLogServices->AddLogs(TransType::DELETE, LogEntity::BUILD_ASSEMBLY, $ID);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        BuildAssembly::where('ID', $ID)
            ->update([
                'STATUS' => $STATUS,
            ]);

        $this->UsersLogServices->StatusLog($STATUS, LogEntity::BUILD_ASSEMBLY, $ID);

    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = BuildAssembly::query()
            ->select([
                'build_assembly.ID',
                'build_assembly.CODE',
                'build_assembly.DATE',
                'build_assembly.AMOUNT',
                'build_assembly.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'i.DESCRIPTION as ITEM_NAME',
            ])
            ->join('item as i', 'i.ID', '=', 'build_assembly.ASSEMBLY_ITEM_ID')
            ->join('document_status_map as s', 's.ID', '=', 'build_assembly.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'build_assembly.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('build_assembly.CODE', 'like', '%' . $search . '%')
                    ->orWhere('build_assembly.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('build_assembly.NOTES', 'like', '%' . $search . '%');
            })
            ->orderBy('build_assembly.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function AutoCreateComponent(int $ASSEMBLY_ITEM_ID, int $BUILD_ASSEMBLY_ID, float $QUANTITY, int $LOCATION_ID): float
    {
        $IS_KIT = false;
        $data   = $this->itemServices->get($ASSEMBLY_ITEM_ID);
        if ($data) {
            $IS_KIT = $data->IS_KIT ?? false;
        }

        $PRICE_LEVEL_ID = 0;
        $locData        = $this->locationServices->get($LOCATION_ID);
        if ($locData) {
            $PRICE_LEVEL_ID = $locData->PRICE_LEVEL_ID;
        }

        $TOTAL = 0;

        if ($IS_KIT) {

            $result = ItemKits::query()
                ->select([
                    'item_kits.COMPONENT_ID',
                    'item_kits.QUANTITY',
                    'item.ASSET_ACCOUNT_ID',
                    DB::raw(" (select  IFNULL(price_level_lines.CUSTOM_COST,0) from price_level_lines join price_level on price_level.ID = price_level_lines.PRICE_LEVEL_ID where price_level.ID = ' . $PRICE_LEVEL_ID . ' and price_level_lines.ITEM_ID = item_kits.COMPONENT_ID ) as RATE "),
                ])
                ->join('item', 'item.ID', '=', 'item_kits.COMPONENT_ID')
                ->where('item_kits.ITEM_ID', '=', $ASSEMBLY_ITEM_ID)
                ->where('item.INACTIVE', '=', 0)
                ->where('item_kits.LOCATION_ID', '=', $LOCATION_ID)
                ->get();
        } else {

            $result = ItemComponents::query()
                ->select([
                    'item_components.COMPONENT_ID',
                    'item_components.QUANTITY',
                    'item.ASSET_ACCOUNT_ID',
                    DB::raw(" (if
                (item_components.RATE > 0, item_components.RATE,
                    (select  IFNULL(price_level_lines.CUSTOM_COST,0) from price_level_lines join price_level on price_level.ID = price_level_lines.PRICE_LEVEL_ID where price_level.ID = ' . $PRICE_LEVEL_ID . ' and price_level_lines.ITEM_ID = item_components.COMPONENT_ID )
                )) as RATE"),
                ])
                ->join('item', 'item.ID', '=', 'item_components.COMPONENT_ID')
                ->where('item_components.ITEM_ID', '=', $ASSEMBLY_ITEM_ID)
                ->where('item.INACTIVE', 0)
                ->get();
        }

        foreach ($result as $item) {
            $QTY    = (float) $item->QUANTITY * $QUANTITY;
            $AMOUNT = (float) ($item->RATE * $item->QUANTITY) * $QUANTITY;
            $TOTAL  = $TOTAL + $AMOUNT;

            $this->ComponentStore($BUILD_ASSEMBLY_ID, $item->COMPONENT_ID, $QTY, $AMOUNT, 0, $item->ASSET_ACCOUNT_ID ?? 0);
        }

        return $TOTAL;
    }
    public function AutoUpdateComponent(int $ASSEMBLY_ITEM_ID, int $BUILD_ASSEMBLY_ID, float $QUANTITY, int $LOCATION_ID): float
    {

        $IS_KIT = false;
        $data   = $this->itemServices->get($ASSEMBLY_ITEM_ID);
        if ($data) {
            $IS_KIT = $data->IS_KIT ?? false;
        }

        $PRICE_LEVEL_ID = 0;
        $locData        = $this->locationServices->get($LOCATION_ID);
        if ($locData) {
            $PRICE_LEVEL_ID = $locData->PRICE_LEVEL_ID;
        }

        $TOTAL = 0;

        if ($IS_KIT) {
            $result = BuildAssemblyItems::query()
                ->select([
                    'build_assembly_items.ID',
                    'build_assembly_items.ITEM_ID',
                    DB::raw('(select QUANTITY from  item_kits where item_kits.COMPONENT_ID =  build_assembly_items.ITEM_ID and item_kits.ITEM_ID = ' . $ASSEMBLY_ITEM_ID . ') as QUANTITY'),
                    DB::raw('(select  IFNull(price_level_lines.CUSTOM_COST,0) from price_level_lines join price_level on price_level.ID = price_level_lines.PRICE_LEVEL_ID where price_level.ID = ' . $PRICE_LEVEL_ID . ' and price_level_lines.ITEM_ID = build_assembly_items.ITEM_ID ) as RATE'),
                ])
                ->join('item', 'item.ID', '=', 'build_assembly_items.ITEM_ID')
                ->where('build_assembly_items.BUILD_ASSEMBLY_ID', '=', $BUILD_ASSEMBLY_ID)
                ->get();
        } else {
            $result = BuildAssemblyItems::query()
                ->select([
                    'build_assembly_items.ID',
                    'build_assembly_items.ITEM_ID',
                    DB::raw('(select QUANTITY from  item_components where item_components.COMPONENT_ID =  build_assembly_items.ITEM_ID and item_components.ITEM_ID = ' . $ASSEMBLY_ITEM_ID . ') as QUANTITY'),
                    DB::raw('(select if (RATE > 0, RATE,
                (select  IFNull(price_level_lines.CUSTOM_COST,0) from price_level_lines join price_level on price_level.ID = price_level_lines.PRICE_LEVEL_ID where price_level.ID = ' . $PRICE_LEVEL_ID . ' and price_level_lines.ITEM_ID = build_assembly_items.ITEM_ID )
                ) from  item_components where item_components.COMPONENT_ID =  build_assembly_items.ITEM_ID and item_components.ITEM_ID = ' . $ASSEMBLY_ITEM_ID . ') as RATE'),
                ])
                ->join('item', 'item.ID', '=', 'build_assembly_items.ITEM_ID')
                ->where('build_assembly_items.BUILD_ASSEMBLY_ID', '=', $BUILD_ASSEMBLY_ID)
                ->get();
        }

        foreach ($result as $item) {

            $QTY    = (float) $item->QUANTITY * $QUANTITY;
            $AMOUNT = (float) ($item->RATE * $item->QUANTITY) * $QUANTITY;
            $TOTAL  = $TOTAL + $AMOUNT;

            $this->ComponentUpdate(
                $item->ID,
                $BUILD_ASSEMBLY_ID,
                $item->ITEM_ID,
                $QTY,
                $AMOUNT
            );
        }

        return $TOTAL;
    }

    public function ComponentStore(int $BUILD_ASSEMBLY_ID, int $ITEM_ID, float $QUANTITY, float $AMOUNT, int $BATCH_ID, int $ASSET_ACCOUNT_ID)
    {
        $ID = (int) $this->object->ObjectNextID('BUILD_ASSEMBLY_ITEMS');

        BuildAssemblyItems::create([
            'ID'                => $ID,
            'BUILD_ASSEMBLY_ID' => $BUILD_ASSEMBLY_ID,
            'ITEM_ID'           => $ITEM_ID,
            'QUANTITY'          => $QUANTITY,
            'AMOUNT'            => $AMOUNT,
            'BATCH_ID'          => $BATCH_ID > 0 ? $BATCH_ID : 0,
            'ASSET_ACCOUNT_ID'  => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : 0,
        ]);

    }
    public function ComponentUpdate(int $ID, int $BUILD_ASSEMBLY_ID, int $ITEM_ID, float $QUANTITY, float $AMOUNT)
    {
        BuildAssemblyItems::where('ID', $ID)
            ->where('BUILD_ASSEMBLY_ID', $BUILD_ASSEMBLY_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'QUANTITY' => $QUANTITY,
                'AMOUNT'   => $AMOUNT,
            ]);
    }
    public function ComponentDelete(int $ID)
    {
        BuildAssemblyItems::where('ID', $ID)->delete();
    }
    public function CountItems(int $BUILD_ASSEMBLY_ID): int
    {
        return (int) BuildAssemblyItems::where('BUILD_ASSEMBLY_ID', $BUILD_ASSEMBLY_ID)->count();
    }
    public function ComponentList(int $BUILD_ASSEMBLY_ID, int $locationId)
    {

        $result = BuildAssemblyItems::query()
            ->select([
                'build_assembly_items.ID',
                'build_assembly_items.QUANTITY',
                'build_assembly_items.AMOUNT',
                'build_assembly_items.BATCH_ID',
                'item.DESCRIPTION',
                'item.CODE',
                'build_assembly_items.ID',
                'u.NAME as UNIT_BASE',
            ])
            ->selectSub(function ($query) use (&$locationId) {
                $query->from('item_inventory')
                    ->select('item_inventory.ENDING_QUANTITY')
                    ->whereColumn('item_inventory.ITEM_ID', 'item.ID')
                    ->where('item_inventory.LOCATION_ID', $locationId)
                    ->orderBy('item_inventory.SOURCE_REF_DATE', 'DESC')
                    ->orderBy('item_inventory.ID', 'DESC')
                    ->limit(1);
            }, 'OTY_OHAND')
            ->join('item', 'item.ID', '=', 'build_assembly_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'item.BASE_UNIT_ID')
            ->where('build_assembly_items.BUILD_ASSEMBLY_ID', $BUILD_ASSEMBLY_ID)
            ->get();

        return $result;
    }
    public function AssemblyItemInventory(int $BUILD_ASSEMBLY_ID)
    {
        $result = BuildAssembly::query()
            ->select([
                'build_assembly.ID',
                'build_assembly.ASSEMBLY_ITEM_ID as ITEM_ID',
                'build_assembly.QUANTITY',
                DB::raw(' 1 as UNIT_BASE_QUANTITY'),
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'build_assembly.ASSEMBLY_ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('build_assembly.ID', $BUILD_ASSEMBLY_ID)
            ->get();

        return $result;
    }
    public function ItemInventory(int $BUILD_ASSEMBLY_ID)
    {
        $result = BuildAssemblyItems::query()
            ->select([
                'build_assembly_items.ID',
                'build_assembly_items.ITEM_ID',
                'build_assembly_items.QUANTITY',
                DB::raw(' 1 as UNIT_BASE_QUANTITY'),
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'build_assembly_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('build_assembly_items.BUILD_ASSEMBLY_ID', $BUILD_ASSEMBLY_ID)
            ->get();

        return $result;
    }

    public function getBuildAssemblyJournal(int $BUILD_ASSEMBLY_ID)
    {
        $result = BuildAssembly::query()
            ->select([
                'ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ASSEMBLY_ITEM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 0 as ENTRY_TYPE'),

            ])
            ->where('ID', $BUILD_ASSEMBLY_ID)->get();

        return $result;
    }
    public function getBuildAssemblyItemsJournal(int $BUILD_ASSEMBLY_ID)
    {
        $result = BuildAssemblyItems::query()
            ->select([
                'ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('BUILD_ASSEMBLY_ID', $BUILD_ASSEMBLY_ID)
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }
}
