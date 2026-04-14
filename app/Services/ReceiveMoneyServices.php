<?php
namespace App\Services;

use App\Models\ReceiveMoney;
use App\Models\ReceiveMoneyDetails;
use Illuminate\Support\Facades\DB;

class ReceiveMoneyServices
{
    public int $object_type_map_receive_money         = 138;
    public int $object_type_map_receive_money_details = 139;
    private $objectServices;
    private $dateServices;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices)
    {

        $this->objectServices        = $objectServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        // Constructor   code here if needed
    }
    public function Get(int $ID)
    {
        return ReceiveMoney::where('ID', '=', $ID)->first();
    }
    public function Store(string $DATE, string $CODE, int $LOCATION_ID, int $ACCOUNT_ID, string $NOTES, bool $IS_XERO = false)
    {
        $ID          = (int) $this->objectServices->ObjectNextID('RECEIVE_MONEY');
        $OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('RECEIVE_MONEY');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));
        ReceiveMoney::create([
            'ID'          => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'DATE'        => $DATE,
            'CODE'        => $CODE !== '' ? $CODE : $this->objectServices->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'LOCATION_ID' => $LOCATION_ID,
            'ACCOUNT_ID'  => $ACCOUNT_ID,
            'NOTES'       => $NOTES,
            'STATUS'      => 0,
            'AMOUNT'      => 0,
            'STATUS_DATE' => $this->dateServices->Now(),
            'IS_XERO'     => $IS_XERO,
        ]);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        ReceiveMoney::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->Now(),
            ]);
    }
    public function Update(int $ID, string $DATE, string $CODE, int $LOCATION_ID, int $ACCOUNT_ID, string $NOTES)
    {
        ReceiveMoney::where('ID', '=', $ID)
            ->update([
                'DATE'        => $DATE,
                'CODE'        => $CODE,
                'LOCATION_ID' => $LOCATION_ID,
                'ACCOUNT_ID'  => $ACCOUNT_ID,
                'NOTES'       => $NOTES,
            ]);

    }

    public function Delete(int $ID)
    {
        ReceiveMoney::where('ID', '=', $ID)->delete();
    }

    public function Search($search, int $locationId = 0)
    {

        $result = ReceiveMoney::query()
            ->select([
                'receive_money.ID',
                'receive_money.DATE',
                'receive_money.CODE',
                'receive_money.NOTES',
                'receive_money.AMOUNT',
                'l.NAME as LOCATION_NAME',
                'document_status_map.DESCRIPTION as STATUS',
                'account.NAME as ACCOUNT_NAME',
            ])

            ->join('account', 'account.ID', '=', 'receive_money.ACCOUNT_ID')
            ->join('document_status_map', 'document_status_map.ID', '=', 'receive_money.STATUS')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'receive_money.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('receive_money.CODE', 'like', '%' . $search . '%')
                    ->orWhere('receive_money.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('receive_money.NOTES', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->paginate(50);

        return $result;
    }

    private function getLine($Id): int
    {
        return (int) ReceiveMoneyDetails::where('RECEIVE_MONEY_ID', $Id)->max('LINE_NO');
    }
    public function StoreDetails(int $RECEIVE_MONEY_ID, int $ACCOUNT_ID, float $AMOUNT, string $NOTES)
    {

        $ID   = (int) $this->objectServices->ObjectNextID('RECEIVE_MONEY_DETAILS');
        $LINE = (int) $this->getLine($RECEIVE_MONEY_ID) + 1;
        ReceiveMoneyDetails::create(['ID' => $ID, 'LINE_NO' => $LINE, 'RECEIVE_MONEY_ID' => $RECEIVE_MONEY_ID, 'ACCOUNT_ID' => $ACCOUNT_ID, 'AMOUNT' => $AMOUNT, 'NOTES' => $NOTES]);
        return $ID;
    }
    public function UpdateDetails(int $ID, int $ACCOUNT_ID, float $AMOUNT, string $NOTES)
    {
        ReceiveMoneyDetails::where('ID', '=', $ID)
            ->update([
                'ACCOUNT_ID' => $ACCOUNT_ID,
                'AMOUNT'     => $AMOUNT,
                'NOTES'      => $NOTES,
            ]);
    }
    public function DeleteDetails(int $ID)
    {
        ReceiveMoneyDetails::where('ID', '=', $ID)->delete();

    }
    public function ListDetails(int $RECEIVE_MONEY_ID)
    {
        $result = ReceiveMoneyDetails::where('RECEIVE_MONEY_ID', '=', $RECEIVE_MONEY_ID)->get();

        return $result;
    }
    public function ReCalculate(int $RECEIVE_MONEY_ID): float
    {
        $total = ReceiveMoneyDetails::where('RECEIVE_MONEY_ID', '=', $RECEIVE_MONEY_ID)->sum('AMOUNT');
        ReceiveMoney::where('ID', '=', $RECEIVE_MONEY_ID)->update(['AMOUNT' => $total]);
        return (float) $total;
    }
    public function getDetailsList(int $ID)
    {
        $result = ReceiveMoneyDetails::query()
            ->select([
                'receive_money_details.ID',
                'receive_money_details.LINE_NO',
                'receive_money_details.AMOUNT',
                'receive_money_details.NOTES',
                'receive_money_details.ACCOUNT_ID',
                'account.NAME as ACCOUNT_NAME',
                'account.TAG as ACCOUNT_CODE',

            ])
            ->join('account', 'account.ID', '=', 'receive_money_details.ACCOUNT_ID')
            ->where('RECEIVE_MONEY_ID', '=', $ID)
            ->get();
        return $result;
    }
    public function getDetails(int $ID)
    {
        $result = ReceiveMoneyDetails::where('ID', '=', $ID)->first();
        return $result;
    }
    public function JournalEntry(int $ID)
    {
        $result = ReceiveMoney::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->where('ID', $ID)
            ->get();

        return $result;
    }
    public function JournalEntryDetails(int $ID)
    {
        $result = ReceiveMoneyDetails::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('RECEIVE_MONEY_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}
