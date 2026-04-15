<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\FundTransfer;
use App\Models\FundTransferReverse;
use Illuminate\Support\Facades\DB;

class FundTransferReverseServices
{
    public int $object_type_id = 144;
    private $objectServices;
    private $dateServices;
    private $systemSettingServices;
    private $usersLogServices;
    private $userServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices, UsersLogServices $usersLogServices, UserServices $userServices)
    {
        $this->objectServices        = $objectServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->usersLogServices      = $usersLogServices;
        $this->userServices          = $userServices;

    }
    public function Get(int $ID)
    {
        $result = FundTransferReverse::where('ID', '=', $ID)->first();

        return $result;
    }
    public function GetFundTransferReverseByFundTransferID(int $FUND_TRANSFER_ID)
    {
        $result = FundTransferReverse::where('FUND_TRANSFER_ID', '=', $FUND_TRANSFER_ID)->first();

        return $result;
    }
    public function ExistsByFundTransferID(int $FUND_TRANSFER_ID): bool
    {
        return FundTransferReverse::where('FUND_TRANSFER_ID', '=', $FUND_TRANSFER_ID)->exists();

    }
    public function Store($DATE, string $NOTES, int $FUND_TRANSFER_ID, int $LOCATION_ID): int
    {

        $ID          = (int) $this->objectServices->ObjectNextID('FUND_TRANSFER');
        $OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('FUND_TRANSFER');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        FundTransferReverse::create([
            'ID'               => $ID,
            'RECORDED_ON'      => $this->dateServices->Now(),
            'DATE'             => $DATE,
            'NOTES'            => $NOTES,
            'FUND_TRANSFER_ID' => $FUND_TRANSFER_ID,
            'USERNAME'         => $this->userServices->GetUsername(),
            'LOCATION_ID'      => $LOCATION_ID,

        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::FUND_TRANSFER_REVERSE, $ID);

        return $ID;
    }
    public function getJournalTo(int $ID, int $FUND_TRANSFER_ID, bool $isDebit, bool $useInter)
    {
        $result = FundTransfer::query()
            ->select([
                DB::raw($ID . ' as ID'),
                ($useInter ? 'INTER_LOCATION_ACCOUNT_ID' : 'TO_ACCOUNT_ID') . ' as ACCOUNT_ID',
                DB::raw(' IFNULL(TO_NAME_ID,0) as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw(($isDebit ? '0' : '1') . ' as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $FUND_TRANSFER_ID)
            ->get();

        return $result;
    }
    public function getJournalFrom(int $ID, int $FUND_TRANSFER_ID, bool $isDebit, bool $useInter)
    {
        $result = FundTransfer::query()
            ->select([
                DB::raw($ID . ' as ID'),
                ($useInter ? 'INTER_LOCATION_ACCOUNT_ID' : 'FROM_ACCOUNT_ID') . ' as ACCOUNT_ID',
                DB::raw(' IFNULL(FROM_NAME_ID,0) as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw(($isDebit ? '0' : '1') . ' as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $FUND_TRANSFER_ID)
            ->get();

        return $result;
    }
}
