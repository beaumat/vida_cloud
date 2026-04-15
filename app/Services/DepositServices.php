<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\Deposit;
use App\Models\DepositFunds;
use App\Models\Payment;
use App\Models\SalesReceipt;
use Illuminate\Support\Facades\DB;

class DepositServices
{

    public int $object_type_deposit      = 81;
    public int $object_type_deposit_fund = 82;

    private $object;
    private $dateServices;
    private $systemSettingServices;
    private $usersLogServices;

    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices, UsersLogServices $usersLogServices)
    {
        $this->object                = $objectServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->usersLogServices      = $usersLogServices;

    }
    public function Get(int $ID)
    {
        $result = Deposit::where('ID', '=', $ID)->first();

        if ($result) {
            return $result;
        }

        return [];
    }

    public function Store(
        string $CODE,
        string $DATE,
        int $BANK_ACCOUNT_ID,
        string $NOTES,
        int $CASH_BACK_ACCOUNT_ID,
        float $CASH_BACK_AMOUNT,
        string $CASH_BACK_NOTES,
        int $LOCATION_ID
    ): int {

        $ID          = $this->object->ObjectNextID('DEPOSIT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('DEPOSIT');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Deposit::create([
            'ID'                   => $ID,
            'RECORDED_ON'          => $this->dateServices->Now(),
            'CODE'                 => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                 => $DATE,
            'BANK_ACCOUNT_ID'      => $BANK_ACCOUNT_ID > 0 ? $BANK_ACCOUNT_ID : null,
            'AMOUNT'               => 0,
            'NOTES'                => $NOTES,
            'CASH_BACK_ACCOUNT_ID' => $CASH_BACK_ACCOUNT_ID > 0 ? $CASH_BACK_ACCOUNT_ID : null,
            'CASH_BACK_AMOUNT'     => $CASH_BACK_AMOUNT,
            'CASH_BACK_NOTES'      => $CASH_BACK_NOTES,
            'LOCATION_ID'          => $LOCATION_ID,
            'STATUS'               => 0,
            'STATUS_DATE'          => $this->dateServices->NowDate(),
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::DEPOSIT, $ID);
        return $ID;
    }
    public function Update(int $ID, string $CODE, int $BANK_ACCOUNT_ID, string $NOTES, int $CASH_BACK_ACCOUNT_ID, float $CASH_BACK_AMOUNT, string $CASH_BACK_NOTES, )
    {
        Deposit::where('ID', '=', $ID)
            ->update([
                'CODE'                 => $CODE,
                'BANK_ACCOUNT_ID'      => $BANK_ACCOUNT_ID > 0 ? $BANK_ACCOUNT_ID : null,
                'NOTES'                => $NOTES,
                'CASH_BACK_ACCOUNT_ID' => $CASH_BACK_ACCOUNT_ID > 0 ? $CASH_BACK_ACCOUNT_ID : null,
                'CASH_BACK_AMOUNT'     => $CASH_BACK_AMOUNT,
                'CASH_BACK_NOTES'      => $CASH_BACK_NOTES,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::DEPOSIT, $ID);
    }
    public function Delete(int $ID)
    {
        DepositFunds::where('DEPOSIT_ID', '=', $ID)->delete();
        Deposit::where('ID', '=', $ID)->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::DEPOSIT, $ID);
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        Deposit::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);
            
        $this->usersLogServices->StatusLog($STATUS, LogEntity::DEPOSIT, $ID);

    }

    public function Search($search, int $locationId, int $perPage)
    {
        $result = Deposit::query()
            ->select([
                'deposit.ID',
                'deposit.CODE',
                'deposit.DATE',
                'deposit.AMOUNT',
                'deposit.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'a.NAME as ACCOUNT_NAME',
            ])
            ->join('account as a', 'a.ID', '=', 'deposit.BANK_ACCOUNT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'deposit.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'deposit.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('deposit.CODE', 'like', '%' . $search . '%')
                        ->orWhere('deposit.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('deposit.NOTES', 'like', '%' . $search . '%')
                        ->orwhere('a.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('deposit.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function StoreFund(
        int $DEPOSIT_ID,
        int $RECEIVED_FROM_ID = 0,
        int $ACCOUNT_ID,
        int $PAYMENT_METHOD_ID,
        string $CHECK_NO,
        float $AMOUNT,
        int $SOURCE_OBJECT_TYPE,
        int $SOURCE_OBJECT_ID
    ) {

        $ID = $this->object->ObjectNextID('DEPOSIT_FUNDS');

        DepositFunds::create([
            'ID'                 => $ID,
            'DEPOSIT_ID'         => $DEPOSIT_ID,
            'RECEIVED_FROM_ID'   => $RECEIVED_FROM_ID > 0 ? $RECEIVED_FROM_ID : null,
            'ACCOUNT_ID'         => $ACCOUNT_ID,
            'PAYMENT_METHOD_ID'  => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
            'CHECK_NO'           => $CHECK_NO,
            'AMOUNT'             => $AMOUNT,
            'SOURCE_OBJECT_TYPE' => $SOURCE_OBJECT_TYPE > 0 ? $SOURCE_OBJECT_TYPE : null,
            'SOURCE_OBJECT_ID'   => $SOURCE_OBJECT_ID > 0 ? $SOURCE_OBJECT_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::DEPOSIT_FUNDS, $DEPOSIT_ID);

        return $ID;
    }
    public function UpdateFund(
        int $ID,
        int $DEPOSIT_ID,
        int $RECEIVED_FROM_ID,
        int $ACCOUNT_ID,
        int $PAYMENT_METHOD_ID,
        string $CHECK_NO,
        float $AMOUNT
    ) {

        DepositFunds::where('ID', '=', $ID)
            ->where('DEPOSIT_ID', '=', $DEPOSIT_ID)
            ->update([
                'RECEIVED_FROM_ID'  => $RECEIVED_FROM_ID > 0 ? $RECEIVED_FROM_ID : null,
                'ACCOUNT_ID'        => $ACCOUNT_ID,
                'PAYMENT_METHOD_ID' => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
                'CHECK_NO'          => $CHECK_NO,
                'AMOUNT'            => $AMOUNT,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::DEPOSIT_FUNDS, $DEPOSIT_ID);
    }
    public function GetFund(int $ID)
    {
        $result = DepositFunds::where('ID', '=', $ID)->first();

        return $result;
    }
    public function DeleteFund(int $ID, int $DEPOSIT_ID)
    {
        DepositFunds::where('ID', '=', $ID)
            ->where('DEPOSIT_ID', '=', $DEPOSIT_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::DEPOSIT_FUNDS, $DEPOSIT_ID);
    }

    public function UndepositedUpdate(int $OBJECT_ID, int $OBJECT_TYPE, int $DEPOSITED)
    {
        switch ($OBJECT_TYPE) {
            case 13:
                # sales_receipt...
                SalesReceipt::where('ID', $OBJECT_ID)->update(['DEPOSITED' => $DEPOSITED]);
                break;
            case 11:
                # payment...
                Payment::where('ID', $OBJECT_ID)->update(['DEPOSITED' => $DEPOSITED]);
                break;
            default:
                # code...
                break;
        }
    }
    public function getAmount($ID): float
    {
        return (float) Deposit::where('ID', '=', $ID)->first()->AMOUNT ?? 0.00;
    }
    public function FundList(int $DEPOSIT_ID)
    {

        $result = DepositFunds::query()
            ->select([
                'deposit_funds.ID',
                'deposit_funds.RECEIVED_FROM_ID',
                'deposit_funds.ACCOUNT_ID',
                'deposit_funds.PAYMENT_METHOD_ID',
                'deposit_funds.CHECK_NO',
                'deposit_funds.AMOUNT',
                'deposit_funds.SOURCE_OBJECT_TYPE',
                'deposit_funds.SOURCE_OBJECT_ID',
                'doc.DESCRIPTION as DOC_NAME',
                'c.PRINT_NAME_AS as RECEIVED_FROM_NAME',
                'p.DESCRIPTION as PAYMENT_METHOD',
                'a.NAME as ACCOUNT_NAME',
            ])
            ->leftJoin('account as a', 'a.ID', '=', 'deposit_funds.ACCOUNT_ID')
            ->leftJoin('contact as c', 'c.ID', 'deposit_funds.RECEIVED_FROM_ID')
            ->leftJoin('payment_method as p', 'p.ID', 'deposit_funds.PAYMENT_METHOD_ID')
            ->leftJoin('account_journal as aj', function ($query) {
                $query->On('aj.OBJECT_TYPE', '=', 'deposit_funds.SOURCE_OBJECT_TYPE')
                    ->On('aj.OBJECT_ID', '=', 'deposit_funds.SOURCE_OBJECT_ID')
                    ->On('aj.ACCOUNT_ID', '=', 'deposit_funds.ACCOUNT_ID');
            })
            ->leftJoin('object_type_map as ob', 'ob.ID', '=', 'aj.OBJECT_ID')
            ->leftJoin('document_type_map as doc', 'doc.ID', '=', 'ob.DOCUMENT_TYPE')
            ->where('deposit_funds.DEPOSIT_ID', '=', $DEPOSIT_ID)
            ->orderBy('deposit_funds.ID', 'asc')
            ->get();

        return $result;
    }

    private function FundSum(int $DEPOSIT_ID)
    {
        return (float) DepositFunds::where('DEPOSIT_ID', '=', $DEPOSIT_ID)->sum('AMOUNT') ?? 0;
    }
    public function UpdateAmount(int $DEPOSIT_ID)
    {
        $TOTAL = (float) $this->FundSum($DEPOSIT_ID);
        Deposit::where('ID', '=', $DEPOSIT_ID)->update(['AMOUNT' => $TOTAL]);
    }

    public function getUndositedCollection(int $LOCATION_ID, int $PAYMENT_METHOD_ID = 0, $SEARCH)
    {

        $collection = DB::table(function ($query) use (&$LOCATION_ID, &$PAYMENT_METHOD_ID, &$SEARCH) {
            $query->select([
                'sr.ID',
                DB::raw('13 AS OBJECT_TYPE'),
                DB::raw("'Sales Receipt' AS TYPE"),
                'sr.DATE',
                'sr.CODE',
                'sr.AMOUNT',
                'c.NAME AS RECEIVED_FROM_NAME',
                'pm.DESCRIPTION AS PAYMENT_METHOD',
            ])
                ->from('sales_receipt AS sr')
                ->join('contact AS c', 'c.ID', '=', 'sr.CUSTOMER_ID')
                ->join('payment_method AS pm', 'pm.ID', '=', 'sr.PAYMENT_METHOD_ID')
                ->where('sr.LOCATION_ID', '=', $LOCATION_ID)
                ->when($PAYMENT_METHOD_ID > 0, function ($sql) use (&$PAYMENT_METHOD_ID) {
                    $sql->where('pm.ID', '=', $PAYMENT_METHOD_ID);
                })
                ->when($SEARCH, function ($query) use (&$SEARCH) {
                    $query->where(function ($sql) use (&$SEARCH) {
                        $sql->where('c.NAME', 'like', '%' . $SEARCH . '%')
                            ->orWhere('sr.CODE', 'like', '%' . $SEARCH . '%');
                    });
                })
                ->where('sr.UNDEPOSITED_FUNDS_ACCOUNT_ID', '=', '5')
                ->where('sr.DEPOSITED', '=', '0')
                ->unionAll(
                    DB::table('payment AS p')
                        ->select([
                            'p.ID',
                            DB::raw('11 AS OBJECT_TYPE'),
                            DB::raw("'Payments' AS TYPE"),
                            'p.DATE',
                            'p.CODE',
                            'p.AMOUNT',
                            'c.NAME AS RECEIVED_FROM_NAME',
                            'pm.DESCRIPTION AS PAYMENT_METHOD',
                        ])
                        ->join('contact AS c', 'c.ID', '=', 'p.CUSTOMER_ID')
                        ->join('payment_method AS pm', 'pm.ID', '=', 'p.PAYMENT_METHOD_ID')
                        ->where('p.LOCATION_ID', '=', $LOCATION_ID)
                        ->when($PAYMENT_METHOD_ID > 0, function ($sql) use (&$PAYMENT_METHOD_ID) {
                            $sql->where('pm.ID', '=', $PAYMENT_METHOD_ID);
                        })
                        ->where('p.UNDEPOSITED_FUNDS_ACCOUNT_ID', '=', '5')
                        ->where('p.DEPOSITED', '=', '0')
                        ->when($SEARCH, function ($query) use (&$SEARCH) {
                            $query->where(function ($sql) use (&$SEARCH) {
                                $sql->where('c.NAME', 'like', '%' . $SEARCH . '%')
                                    ->orWhere('p.CODE', 'like', '%' . $SEARCH . '%');
                            });
                        })
                );
        }, 'collection')
            ->orderBy('collection.DATE')
            ->get();

        return $collection;
    }
    public function getSalesReceipt(int $SR_ID): int
    {
        $result = DepositFunds::query()
            ->select(['DEPOSIT_ID'])
            ->where('SOURCE_OBJECT_ID', $SR_ID)
            ->where('SOURCE_OBJECT_TYPE', 13)
            ->first();

        if ($result) {

            return (int) $result->DEPOSIT_ID;
        }
        return 0;

    }
    public function getPayment(int $PAYMENT_ID): int
    {
        $result = DepositFunds::query()
            ->select(['DEPOSIT_ID'])
            ->where('SOURCE_OBJECT_ID', $PAYMENT_ID)
            ->where('SOURCE_OBJECT_TYPE', 11)
            ->first();

        if ($result) {

            return (int) $result->DEPOSIT_ID;
        }
        return 0;

    }
    public function DepositJournal(int $DEPOSIT_ID)
    {
        $result = Deposit::query()
            ->select([
                'ID',
                'BANK_ACCOUNT_ID as ACCOUNT_ID',
                DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $DEPOSIT_ID)
            ->get();

        return $result;
    }

    public function DepositFundJournal(int $DEPOSIT_ID)
    {
        $result = DepositFunds::query()
            ->select([
                'deposit_funds.ID',
                'deposit_funds.ACCOUNT_ID',
                'deposit_funds.RECEIVED_FROM_ID as SUBSIDIARY_ID',
                'deposit_funds.AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('deposit_funds.DEPOSIT_ID', '=', $DEPOSIT_ID)
            ->get();

        return $result;
    }
}
