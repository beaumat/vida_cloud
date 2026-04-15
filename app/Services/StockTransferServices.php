<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\StockTransfer;
use App\Models\StockTransferItems;
use Illuminate\Support\Facades\DB;

class StockTransferServices
{
    public int $object_type_stock_transfer       = 38;
    public int $object_type_stock_transfer_items = 39;
    public int $document_type_id                 = 7;

    private $dateServices;
    private $systemSettingServices;
    private $object;

    private $usersLogServices;

    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices, UsersLogServices $usersLogServices)
    {
        $this->object                = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function Get(int $ID)
    {
        return StockTransfer::where('ID', $ID)->first();
    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID, int $TRANSFER_TO_ID, string $NOTES, int $PREPARED_BY_ID, int $ACCOUNT_ID): int
    {

        $ID          = $this->object->ObjectNextID('STOCK_TRANSFER');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('STOCK_TRANSFER');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        StockTransfer::create([
            'ID'             => $ID,
            'RECORDED_ON'    => $this->dateServices->Now(),
            'CODE'           => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'           => $DATE,
            'LOCATION_ID'    => $LOCATION_ID,
            'TRANSFER_TO_ID' => $TRANSFER_TO_ID > 0 ? $TRANSFER_TO_ID : null,
            'AMOUNT'         => 0,
            'RETAIL_VALUE'   => 0,
            'NOTES'          => $NOTES,
            'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
            'STATUS'         => 0,
            'STATUS_DATE'    => $this->dateServices->NowDate(),
            'ACCOUNT_ID'     => $ACCOUNT_ID,
        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::STOCK_TRANSFER, $ID);
        return $ID;
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        StockTransfer::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::STOCK_TRANSFER, $ID);
    }
    public function Update(int $ID, string $CODE, int $TRANSFER_TO_ID, string $NOTES, int $PREPARED_BY_ID)
    {
        StockTransfer::where('ID', $ID)
            ->update([
                'CODE'           => $CODE,
                'TRANSFER_TO_ID' => $TRANSFER_TO_ID,
                'NOTES'          => $NOTES,
                'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
            ]);
        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::STOCK_TRANSFER, $ID);
    }

    public function Delete(int $ID)
    {
        StockTransferItems::where('STOCK_TRANSFER_ID', $ID)->delete();
        StockTransfer::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::STOCK_TRANSFER, $ID);
    }

    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = StockTransfer::query()
            ->select([
                'stock_transfer.ID',
                'stock_transfer.CODE',
                'stock_transfer.DATE',
                'stock_transfer.AMOUNT',
                'stock_transfer.RETAIL_VALUE',
                'stock_transfer.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                't.NAME as TRANSFER_TO',
                'c.NAME as PREPARED_BY',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'stock_transfer.PREPARED_BY_ID')
            ->join('location as t', 't.ID', '=', 'stock_transfer.TRANSFER_TO_ID')
            ->join('document_status_map as s', 's.ID', '=', 'stock_transfer.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'stock_transfer.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('stock_transfer.CODE', 'like', '%' . $search . '%')
                    ->orWhere('stock_transfer.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('stock_transfer.NOTES', 'like', '%' . $search . '%');
            })
            ->orderBy('stock_transfer.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function CountItems(int $STOCK_TRANSFER_ID): int
    {
        return (int) StockTransferItems::where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)->count();
    }
    public function HasAlreadyItem(int $STOCK_TRANSFER_ID): bool
    {
        return (bool) StockTransferItems::where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)->exists();
    }
    private function getLine($STOCK_TRANSFER_ID): int
    {
        return (int) StockTransferItems::where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)->max('LINE_NO');
    }
    public function ItemStore(
        int $STOCK_TRANSFER_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $UNIT_COST,
        float $UNIT_PRICE,
        int $BATCH_ID,
        int $ASSET_ACCOUNT_ID
    ) {

        $ID = $this->object->ObjectNextID('STOCK_TRANSFER_ITEMS');

        $LINE_NO = $this->getLine($STOCK_TRANSFER_ID) + 1;

        StockTransferItems::create([
            'ID'                 => $ID,
            'STOCK_TRANSFER_ID'  => $STOCK_TRANSFER_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
            'DESCRIPTION'        => null,
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'UNIT_COST'          => $UNIT_COST,
            'UNIT_PRICE'         => $UNIT_PRICE,
            'AMOUNT'             => $UNIT_COST * $QUANTITY,
            'RETAIL_VALUE'       => $UNIT_PRICE * $QUANTITY,
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'ASSET_ACCOUNT_ID'   => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::STOCK_TRANSFER_ITEMS, $STOCK_TRANSFER_ID);
        $this->UpdateTotal($STOCK_TRANSFER_ID);
    }
    public function GetItem(int $ID, int $STOCK_TRANSFER_ID)
    {
        return StockTransferItems::where('ID', $ID)
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->first();
    }
    public function ItemUpdate(
        int $ID,
        int $STOCK_TRANSFER_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $UNIT_COST,
        float $UNIT_PRICE,
        int $BATCH_ID
    ) {
        StockTransferItems::where('ID', $ID)
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'ID'                 => $ID,
                'ITEM_ID'            => $ITEM_ID,
                'QUANTITY'           => $QUANTITY,
                'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'UNIT_COST'          => $UNIT_COST,
                'UNIT_PRICE'         => $UNIT_PRICE,
                'AMOUNT'             => $UNIT_COST * $QUANTITY,
                'RETAIL_VALUE'       => $UNIT_PRICE * $QUANTITY,
                'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            ]);
        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::STOCK_TRANSFER_ITEMS, $STOCK_TRANSFER_ID);
        $this->UpdateTotal($STOCK_TRANSFER_ID);
    }
    public function ItemDelete(int $ID, int $STOCK_TRANSFER_ID)
    {
        StockTransferItems::where('ID', $ID)
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::STOCK_TRANSFER_ITEMS, $STOCK_TRANSFER_ID);
        $this->UpdateTotal($STOCK_TRANSFER_ID);
    }

    public function ItemGet(int $ID, int $STOCK_TRANSFER_ID)
    {
        $result = StockTransferItems::where('ID', '=', $ID)
            ->where('STOCK_TRANSFER_ID', '=', $STOCK_TRANSFER_ID)
            ->first();

        if ($result) {
            return $result;
        }
        return [];
    }
    public function ItemView(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransferItems::query()
            ->select([
                'stock_transfer_items.ID',
                'stock_transfer_items.ITEM_ID',
                'stock_transfer_items.QUANTITY',
                'stock_transfer_items.UNIT_ID',
                'stock_transfer_items.UNIT_COST',
                'stock_transfer_items.UNIT_PRICE',
                'stock_transfer_items.AMOUNT',
                'stock_transfer_items.RETAIL_VALUE',
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                'stock_transfer_items.ASSET_ACCOUNT_ID',
            ])
            ->leftJoin('item', 'item.ID', '=', 'stock_transfer_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'stock_transfer_items.UNIT_ID')
            ->where('stock_transfer_items.STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->orderBy('stock_transfer_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    private function GetTotal(int $STOCK_TRANSFER_ID)
    {

        $result = StockTransferItems::query()
            ->select([
                DB::raw(' ifnull( sum(AMOUNT),2) as AMOUNT'),
                DB::raw(' ifnull( sum(RETAIL_VALUE),2) as RETAIL_VALUE'),
            ])
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->first();

        if ($result) {
            return [
                'AMOUNT'       => $result->AMOUNT,
                'RETAIL_VALUE' => $result->RETAIL_VALUE,
            ];
        }

        return [
            'AMOUNT'       => 0,
            'RETAIL_VALUE' => 0,
        ];
    }
    public function GetSum(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransfer::query()
            ->select([
                'AMOUNT',
                'RETAIL_VALUE',
            ])
            ->where('ID', $STOCK_TRANSFER_ID)
            ->first();

        if ($result) {
            return [
                'AMOUNT'       => $result->AMOUNT,
                'RETAIL_VALUE' => $result->RETAIL_VALUE,
            ];
        }

        return [
            'AMOUNT'       => 0,
            'RETAIL_VALUE' => 0,
        ];
    }
    public function UpdateTotal(int $STOCK_TRANSFER_ID)
    {
        $result = $this->GetTotal($STOCK_TRANSFER_ID);
        StockTransfer::where('ID', $STOCK_TRANSFER_ID)
            ->update(
                [
                    'AMOUNT'       => $result['AMOUNT'],
                    'RETAIL_VALUE' => $result['RETAIL_VALUE'],
                ]
            );
    }
    public function ItemInventory(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransferItems::query()
            ->select([
                'stock_transfer_items.ID',
                'stock_transfer_items.ITEM_ID',
                'stock_transfer_items.QUANTITY',
                'stock_transfer_items.UNIT_BASE_QUANTITY',
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'stock_transfer_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('stock_transfer_items.STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->get();

        return $result;
    }
    public function getStockTransferJournal_Source(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransfer::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw(" 0 as SUBSIDIARY_ID"),
                'AMOUNT',
                DB::raw(" 0 as ENTRY_TYPE"),
                DB::raw("'SOURCEACCOUNT' as EXTENDED_OPTIONS"),
                DB::raw("YEAR(DATE) as SEQUENCE_GROUP"),
            ])
            ->where('ID', $STOCK_TRANSFER_ID)->get();

        return $result;
    }
    public function getStockTransferJournal_Des(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransfer::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw(" 0 as SUBSIDIARY_ID"),
                'AMOUNT',
                DB::raw("1 as ENTRY_TYPE"),
                DB::raw("'DESTACCOUNT' as EXTENDED_OPTIONS"),
                DB::raw("YEAR(DATE) as SEQUENCE_GROUP"),

            ])
            ->where('ID', $STOCK_TRANSFER_ID)->get();

        return $result;
    }

    public function getStockTransferItemJournal_Debit(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransferItems::query()
            ->select([
                'ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
                DB::raw("'DESTACCOUNT' as EXTENDED_OPTIONS"),
            ])
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getStockTransferItemJournal_Credit(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransferItems::query()
            ->select([
                'ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
                DB::raw("'SOURCEACCOUNT' as EXTENDED_OPTIONS"),
            ])
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getStockTransferReceiver($search, int $LOCATION_ID, int $perPage)
    {
        $result = StockTransfer::query()
            ->select([
                'stock_transfer.ID',
                'stock_transfer.CODE',
                'stock_transfer.DATE',
                'stock_transfer.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                't.NAME as TRANSFER_FROM',
                'c.NAME as PREPARED_BY',
                'stock_transfer.RECORDED_ON',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'stock_transfer.PREPARED_BY_ID')
            ->join('location as t', 't.ID', '=', 'stock_transfer.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'stock_transfer.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'stock_transfer.TRANSFER_TO_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('stock_transfer.CODE', 'like', '%' . $search . '%')
                    ->orWhere('stock_transfer.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('stock_transfer.NOTES', 'like', '%' . $search . '%');
            })
            ->where('STATUS', '=', 15)
            ->orderBy('stock_transfer.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
}
