<?php
namespace App\Services;

use App\Models\BankTransfer;
use Illuminate\Support\Facades\DB;

class BankTransferServices
{

    public int $object_type_id = 135; //
    private $object;
    private $dateServices;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices)
    {
        $this->object                = $objectServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function Get(int $ID)
    {
        $result = BankTransfer::where('ID', '=', $ID)->first();
        return $result;
    }
    public function Store($DATE, string $CODE, int $FROM_BANK_ACCOUNT_ID, int $TO_BANK_ACCOUNT_ID, int $FROM_NAME_ID, int $TO_NAME_ID, int $FROM_LOCATION_ID, int $TO_LOCATION_ID, int $INTER_LOCATION_ACCOUNT_ID, string $NOTES, float $AMOUNT): int
    {

        $ID          = (int) $this->object->ObjectNextID('BANK_TRANSFER');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('BANK_TRANSFER');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        BankTransfer::create([
            'ID'                        => $ID,
            'RECORDED_ON'               => $this->dateServices->Now(),
            'DATE'                      => $DATE,
            'CODE'                      => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $FROM_LOCATION_ID : null),
            'FROM_BANK_ACCOUNT_ID'      => $FROM_BANK_ACCOUNT_ID,
            'TO_BANK_ACCOUNT_ID'        => $TO_BANK_ACCOUNT_ID,
            'FROM_NAME_ID'              => $FROM_NAME_ID,
            'TO_NAME_ID'                => $TO_NAME_ID,
            'FROM_LOCATION_ID'          => $FROM_LOCATION_ID,
            'TO_LOCATION_ID'            => $TO_LOCATION_ID,
            'INTER_LOCATION_ACCOUNT_ID' => $INTER_LOCATION_ACCOUNT_ID > 0 ? $INTER_LOCATION_ACCOUNT_ID : null,
            'CLASS_ID'                  => null,
            'AMOUNT'                    => $AMOUNT,
            'NOTES'                     => $NOTES,
        ]);

        return $ID;
    }
    public function Update(int $ID, string $CODE, int $FROM_BANK_ACCOUNT_ID, int $TO_BANK_ACCOUNT_ID, int $FROM_NAME_ID, int $TO_NAME_ID, int $FROM_LOCATION_ID, int $TO_LOCATION_ID, int $INTER_LOCATION_ACCOUNT_ID, string $NOTES, float $AMOUNT)
    {

        BankTransfer::where('ID', '=', $ID)
            ->update([
                'ID'                        => $ID,
                'CODE'                      => $CODE,
                'FROM_BANK_ACCOUNT_ID'      => $FROM_BANK_ACCOUNT_ID,
                'TO_BANK_ACCOUNT_ID'        => $TO_BANK_ACCOUNT_ID,
                'FROM_NAME_ID'              => $FROM_NAME_ID > 0 ? $FROM_NAME_ID : null,
                'TO_NAME_ID'                => $TO_NAME_ID > 0 ? $TO_NAME_ID : null,
                'FROM_LOCATION_ID'          => $FROM_LOCATION_ID,
                'TO_LOCATION_ID'            => $TO_LOCATION_ID,
                'INTER_LOCATION_ACCOUNT_ID' => $INTER_LOCATION_ACCOUNT_ID > 0 ? $INTER_LOCATION_ACCOUNT_ID : null,
                'CLASS_ID'                  => null,
                'NOTES'                     => $NOTES,
                'AMOUNT'                    => $AMOUNT,
            ]);
    }
    public function Delete(int $ID)
    {
        BankTransfer::where('ID', '=', $ID)->delete();
    }
    public function Search($search, int $locationId)
    {

        $result = BankTransfer::query()
            ->select([
                'bank_transfer.ID',
                'bank_transfer.CODE',
                'bank_transfer.DATE',
                'l.NAME as LOCATION_FROM',
                DB::raw("(select location.NAME from location where ID = bank_transfer.TO_LOCATION_ID ) as LOCATION_TO"),
                DB::raw("(select contact.PRINT_NAME_AS from contact  where ID = bank_transfer.FROM_NAME_ID) as FROM_NAME"),
                DB::raw("(select contact.PRINT_NAME_AS from contact  where ID = bank_transfer.TO_NAME_ID) as TO_NAME"),
                'bank_transfer.NOTES',
                'bank_transfer.AMOUNT',
                'f.NAME as FROM_BANK_NAME',
                't.NAME as TO_BANK_NAME',
            ])
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'bank_transfer.FROM_LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('account as f', 'f.ID', '=', 'bank_transfer.FROM_BANK_ACCOUNT_ID')
            ->join('account as t', 't.ID', '=', 'bank_transfer.TO_BANK_ACCOUNT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bank_transfer.CODE', 'like', '%' . $search . '%')
                        ->orWhere('bank_transfer.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('l.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('bank_transfer.ID', 'desc')
            ->paginate(30);

        return $result;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        BankTransfer::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);
    }
    public function getJournalTo(int $ID, bool $isDebit, bool $useInter)
    {
        $result = BankTransfer::query()
            ->select([
                'ID',
                ($useInter ? 'INTER_LOCATION_ACCOUNT_ID' : 'TO_BANK_ACCOUNT_ID') . ' as ACCOUNT_ID',
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
        $result = BankTransfer::query()
            ->select([
                'ID',
                ($useInter ? 'INTER_LOCATION_ACCOUNT_ID' : 'FROM_BANK_ACCOUNT_ID') . ' as ACCOUNT_ID',
                DB::raw(' IFNULL(FROM_NAME_ID,0) as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw(($isDebit ? '0' : '1') . ' as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $ID)
            ->get();

        return $result;
    }

}
