<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\FundTransfer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FundTransferServices
{
    public int $object_type_id = 93;
    private $objectServices;
    private $dateServices;
    private $systemSettingServices;
    private $usersLogServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices, UsersLogServices $usersLogServices)
    {
        $this->objectServices        = $objectServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->usersLogServices      = $usersLogServices;

    }
    public function Get(int $ID)
    {
        $result = FundTransfer::where('ID', '=', $ID)->first();

        return $result;
    }

    public function Exists(int $ID): bool
    {
        return FundTransfer::where('ID', '=', $ID)->exists();

    }
    public function Store($DATE, string $CODE, int $FROM_ACCOUNT_ID, int $TO_ACCOUNT_ID, int $FROM_NAME_ID, int $TO_NAME_ID, int $FROM_LOCATION_ID, int $TO_LOCATION_ID, int $INTER_LOCATION_ACCOUNT_ID, string $NOTES, float $AMOUNT): int
    {

        $ID          = (int) $this->objectServices->ObjectNextID('FUND_TRANSFER');
        $OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('FUND_TRANSFER');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        FundTransfer::create([
            'ID'                        => $ID,
            'RECORDED_ON'               => $this->dateServices->Now(),
            'DATE'                      => $DATE,
            'CODE'                      => $CODE !== '' ? $CODE : $this->objectServices->GetSequence($OBJECT_TYPE, $isLocRef ? $FROM_LOCATION_ID : null),
            'FROM_ACCOUNT_ID'           => $FROM_ACCOUNT_ID,
            'TO_ACCOUNT_ID'             => $TO_ACCOUNT_ID,
            'FROM_NAME_ID'              => $FROM_NAME_ID,
            'TO_NAME_ID'                => $TO_NAME_ID,
            'FROM_LOCATION_ID'          => $FROM_LOCATION_ID,
            'TO_LOCATION_ID'            => $TO_LOCATION_ID,
            'INTER_LOCATION_ACCOUNT_ID' => $INTER_LOCATION_ACCOUNT_ID > 0 ? $INTER_LOCATION_ACCOUNT_ID : null,
            'CLASS_ID'                  => null,
            'AMOUNT'                    => $AMOUNT,
            'NOTES'                     => $NOTES,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::FUND_TRANSFER, $ID);
        return $ID;
    }
    public function Update(int $ID, string $CODE, int $FROM_ACCOUNT_ID, int $TO_ACCOUNT_ID, int $FROM_NAME_ID, int $TO_NAME_ID, int $FROM_LOCATION_ID, int $TO_LOCATION_ID, int $INTER_LOCATION_ACCOUNT_ID, string $NOTES, float $AMOUNT)
    {

        FundTransfer::where('ID', '=', $ID)
            ->update([
                'ID'                        => $ID,
                'CODE'                      => $CODE,
                'FROM_ACCOUNT_ID'           => $FROM_ACCOUNT_ID,
                'TO_ACCOUNT_ID'             => $TO_ACCOUNT_ID,
                'FROM_NAME_ID'              => $FROM_NAME_ID > 0 ? $FROM_NAME_ID : null,
                'TO_NAME_ID'                => $TO_NAME_ID > 0 ? $TO_NAME_ID : null,
                'FROM_LOCATION_ID'          => $FROM_LOCATION_ID,
                'TO_LOCATION_ID'            => $TO_LOCATION_ID,
                'INTER_LOCATION_ACCOUNT_ID' => $INTER_LOCATION_ACCOUNT_ID > 0 ? $INTER_LOCATION_ACCOUNT_ID : null,
                'CLASS_ID'                  => null,
                'NOTES'                     => $NOTES,
                'AMOUNT'                    => $AMOUNT,
            ]);
        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::FUND_TRANSFER, $ID);
    }
    public function Delete(int $ID)
    {
        FundTransfer::where('ID', '=', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::FUND_TRANSFER, $ID);
    }
    public function Search($search, int $locationId): LengthAwarePaginator
    {

        $result = FundTransfer::query()
            ->select([
                'fund_transfer.ID',
                'fund_transfer.CODE',
                'fund_transfer.DATE',
                'l.NAME as LOCATION_FROM',
                DB::raw("(select location.NAME from location where ID = fund_transfer.TO_LOCATION_ID ) as LOCATION_TO"),
                DB::raw("(select contact.PRINT_NAME_AS from contact  where ID = fund_transfer.FROM_NAME_ID) as FROM_NAME"),
                DB::raw("(select contact.PRINT_NAME_AS from contact  where ID = fund_transfer.TO_NAME_ID) as TO_NAME"),
                'fund_transfer.NOTES',
                'fund_transfer.AMOUNT',
                's.DESCRIPTION as STATUS',
                'fund_transfer.STATUS as STATUS_ID',
            ])
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'fund_transfer.FROM_LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'fund_transfer.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('fund_transfer.CODE', 'like', '%' . $search . '%')
                        ->orWhere('fund_transfer.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('l.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('fund_transfer.ID', 'desc')
            ->paginate(30);

        return $result;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        FundTransfer::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::FUND_TRANSFER, $ID);

    }
    public function getJournalTo(int $ID, bool $isDebit, bool $useInter)
    {
        $result = FundTransfer::query()
            ->select([
                'ID',
                ($useInter ? 'INTER_LOCATION_ACCOUNT_ID' : 'TO_ACCOUNT_ID') . ' as ACCOUNT_ID',
                DB::raw(' IFNULL(TO_NAME_ID,0) as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw(($isDebit ? '0' : '1') . ' as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $ID)
            ->get();

        return $result;
    }
    public function getJournalFrom(int $ID, bool $isDebit, bool $useInter)
    {
        $result = FundTransfer::query()
            ->select([
                'ID',
                ($useInter ? 'INTER_LOCATION_ACCOUNT_ID' : 'FROM_ACCOUNT_ID') . ' as ACCOUNT_ID',
                DB::raw(' IFNULL(FROM_NAME_ID,0) as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw(($isDebit ? '0' : '1') . ' as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $ID)
            ->get();

        return $result;
    }
}
