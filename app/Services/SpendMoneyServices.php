<?php
namespace App\Services;

use App\Models\SpendMoney;
use App\Models\SpendMoneyDetails;
use Illuminate\Support\Facades\DB;

class SpendMoneyServices
{

    public int $object_type_map_spend_money = 136;
    public int $object_type_map_spend_money_details = 137;
    private $objectServices;
    private $dateServices;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices)
    {

        $this->objectServices = $objectServices;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        // Constructor   code here if needed
    }
    public function Get(int $ID)
    {
        return SpendMoney::where('ID', '=', $ID)->first();
    }
    public function Store(string $DATE, string $CODE, int $LOCATION_ID, int $ACCOUNT_ID, string $NOTES, bool $IS_XERO = false)
    {
        $ID = (int) $this->objectServices->ObjectNextID('SPEND_MONEY');
        $OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('SPEND_MONEY');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));
        SpendMoney::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'DATE' => $DATE,
            'CODE' => $CODE !== '' ? $CODE : $this->objectServices->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'LOCATION_ID' => $LOCATION_ID,
            'ACCOUNT_ID' => $ACCOUNT_ID,
            'NOTES' => $NOTES,
            'STATUS' => 0,
            'AMOUNT' => 0,
            'STATUS_DATE' => $this->dateServices->Now(),
            'IS_XERO' => $IS_XERO
        ]);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        SpendMoney::where('ID', '=', $ID)
            ->update([
                'STATUS' => $STATUS,
                'STATUS_DATE' => $this->dateServices->Now(),
            ]);
    }
    public function Update(int $ID, string $DATE, string $CODE, int $LOCATION_ID, int $ACCOUNT_ID, string $NOTES)
    {
        SpendMoney::where('ID', '=', $ID)
            ->update([
                'DATE' => $DATE,
                'CODE' => $CODE,
                'LOCATION_ID' => $LOCATION_ID,
                'ACCOUNT_ID' => $ACCOUNT_ID,
                'NOTES' => $NOTES,
            ]);

    }

    public function Delete(int $ID)
    {
        SpendMoney::where('ID', '=', $ID)->delete();
    }

    public function Search($search, int $locationId = 0)
    {

        $result = SpendMoney::query()
            ->select([
                'spend_money.ID',
                'spend_money.DATE',
                'spend_money.CODE',
                'spend_money.NOTES',
                'spend_money.AMOUNT',
                'l.NAME as LOCATION_NAME',
                'document_status_map.DESCRIPTION as STATUS',
                'account.NAME as ACCOUNT_NAME',
            ])
            ->join('account', 'account.ID', '=', 'spend_money.ACCOUNT_ID')
            ->join('document_status_map', 'document_status_map.ID', '=', 'spend_money.STATUS')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'spend_money.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('spend_money.CODE', 'like', '%' . $search . '%')
                    ->orWhere('spend_money.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('spend_money.NOTES', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->paginate(50);

        return $result;
    }

    private function getLine($Id): int
    {
        return (int) SpendMoneyDetails::where('SPEND_MONEY_ID', $Id)->max('LINE_NO');
    }
    public function StoreDetails(int $SPEND_MONEY_ID, int $ACCOUNT_ID, float $AMOUNT, string $NOTES)
    {

        $ID = (int) $this->objectServices->ObjectNextID('SPEND_MONEY_DETAILS');
        $LINE = (int) $this->getLine($SPEND_MONEY_ID) + 1;
        SpendMoneyDetails::create(['ID' => $ID, 'LINE_NO' => $LINE, 'SPEND_MONEY_ID' => $SPEND_MONEY_ID, 'ACCOUNT_ID' => $ACCOUNT_ID, 'AMOUNT' => $AMOUNT, 'NOTES' => $NOTES,]);
        return $ID;
    }
    public function UpdateDetails(int $ID, int $ACCOUNT_ID, float $AMOUNT, string $NOTES)
    {
        SpendMoneyDetails::where('ID', '=', $ID)
            ->update([
                'ACCOUNT_ID' => $ACCOUNT_ID,
                'AMOUNT' => $AMOUNT,
                'NOTES' => $NOTES,
            ]);
    }
    public function DeleteDetails(int $ID)
    {
        SpendMoneyDetails::where('ID', '=', $ID)->delete();

    }
    public function ListDetails(int $SPEND_MONEY_ID)
    {
        $result = SpendMoneyDetails::where('SPEND_MONEY_ID', '=', $SPEND_MONEY_ID)->get();

        return $result;
    }
    public function ReCalculate(int $SPEND_MONEY_ID): float
    {
        $total = SpendMoneyDetails::where('SPEND_MONEY_ID', '=', $SPEND_MONEY_ID)->sum('AMOUNT');
        SpendMoney::where('ID', '=', $SPEND_MONEY_ID)->update(['AMOUNT' => $total]);
        return (float) $total;
    }
    public function getDetailsList(int $ID)
    {
        $result = SpendMoneyDetails::query()
            ->select([
                'spend_money_details.ID',
                'spend_money_details.LINE_NO',
                'spend_money_details.AMOUNT',
                'spend_money_details.NOTES',
                'spend_money_details.ACCOUNT_ID',
                'account.NAME as ACCOUNT_NAME',
                'account.TAG as ACCOUNT_CODE',

            ])
            ->join('account', 'account.ID', '=', 'spend_money_details.ACCOUNT_ID')
            ->where('SPEND_MONEY_ID', '=', $ID)
            ->get();
        return $result;
    }
    public function getDetails(int $ID)
    {
        $result = SpendMoneyDetails::where('ID', '=', $ID)->first();
        return $result;
    }
    public function JournalEntry(int $ID)
    {
        $result = SpendMoney::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('ID', $ID)
            ->get();

        return $result;
    }
    public function JournalEntryDetails(int $ID)
    {
        $result = SpendMoneyDetails::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->where('SPEND_MONEY_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}