<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\Accounts;
use App\Models\AccountType;
use Illuminate\Support\Facades\DB;

class AccountServices
{
    public int $ACCOUNTS_RECEIVABLE_ID = 4;
    public int $UNDEPOSITED_ACCOUNT_ID = 5;
    public int $EXPENSE_ACCOUNT_ID     = 284;
    private $object;
    private $usersLogServices;
    public function __construct(ObjectServices $objectService, UsersLogServices $usersLogServices)
    {
        $this->object = $objectService;

        $this->usersLogServices = $usersLogServices;
    }
    public function GetTypeList()
    {
        $result = AccountType::get();

        return $result;
    }
    public function getBankAccount()
    {
        return Accounts::whereIn('TYPE', ['0', '6'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }

    public function getBankAccountDeposit()
    {
        return Accounts::whereIn('TYPE', ['0', '6'])
            ->where('INACTIVE', '=', '0')
            ->orWhere('ID', '=', $this->UNDEPOSITED_ACCOUNT_ID)
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getUndepositedList()
    {
        return Accounts::whereIn('TYPE', ['6'])
            ->where('INACTIVE', '=', '0')
            ->orWhere('ID', '=', $this->UNDEPOSITED_ACCOUNT_ID)
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getExpenses()
    {
        return Accounts::whereIn('TYPE', ['12', '14'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getPayable()
    {
        return Accounts::whereIn('TYPE', ['5', '6', '7', '8'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getCost()
    {
        return Accounts::where('TYPE', '=', '11')
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getReceivable()
    {
        $result = Accounts::whereIn('TYPE', ['0', '1', '2', '3', '4'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();

        return $result;
    }
    public function getIncome()
    {
        return Accounts::whereIn('TYPE', ['7', '8', '10', '13'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getPay()
    {
        return Accounts::whereIn('TYPE', ['0', '1', '2'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function get(int $ID)
    {
        return Accounts::where('ID', $ID)->first();
    }
    public function getByName(string $NAME): int
    {
        $data = Accounts::where('NAME', $NAME)->first();
        if ($data) {
            return $data->ID;
        }

        return 0;
    }
    public function getAccount(bool $isCode)
    {

        if ($isCode) {

            $result = Accounts::query()
                ->select(['ID', 'TAG as CODE'])
                ->where('INACTIVE', '=', '0')
                ->orderBy('TAG', 'asc')
                ->get();

            return $result;
        }

        $result = Accounts::query()
            ->select([
                'account.ID',
                'account.NAME as DESCRIPTION',
                't.DESCRIPTION as TYPE',
            ])
            ->join('account_type_map as t', 't.ID', '=', 'account.TYPE')
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();

        return $result;
    }

    public function Store(string $NAME, int $GROUP_ACCOUNT_ID, int $TYPE, string $BANK_ACCOUNT_NO, bool $INACTIVE, string $TAG, int $LINE_NO): int
    {
        $ID = $this->object->ObjectNextID('ACCOUNT');

        Accounts::create([
            'ID'               => $ID,
            'NAME'             => $NAME,
            'GROUP_ACCOUNT_ID' => $GROUP_ACCOUNT_ID > 0 ? $GROUP_ACCOUNT_ID : null,
            'TYPE'             => $TYPE,
            'BANK_ACCOUNT_NO'  => $BANK_ACCOUNT_NO,
            'INACTIVE'         => $INACTIVE,
            'TAG'              => $TAG,
            'LINE_NO'          => $LINE_NO,

        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::ACCOUNT, $ID);

        return $ID;
    }

    public function Update(int $ID, string $NAME, int $GROUP_ACCOUNT_ID, int $TYPE, string $BANK_ACCOUNT_NO, bool $INACTIVE, string $TAG, int $LINE_NO): void
    {

        Accounts::where('ID', '=', $ID)
            ->update([
                'NAME'             => $NAME,
                'GROUP_ACCOUNT_ID' => $GROUP_ACCOUNT_ID > 0 ? $GROUP_ACCOUNT_ID : null,
                'TYPE'             => $TYPE,
                'BANK_ACCOUNT_NO'  => $BANK_ACCOUNT_NO,
                'INACTIVE'         => $INACTIVE,
                'TAG'              => $TAG,
                'LINE_NO'          => $LINE_NO,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::ACCOUNT, $ID);
    }

    public function Delete(int $ID): void
    {
        Accounts::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::ACCOUNT, $ID);
    }
    public function Inactive(int $ID, int $stats)
    {
        Accounts::where('ID', $ID)->update(['INACTIVE' => $stats]);
        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::ACCOUNT, $ID);
    }
    public function Search($search, int $LOCATION_ID, bool $showAll, bool $showBalance = false)
    {
        $EB_SQL = $showBalance == false ? "0" : "(SELECT  IFNULL( account_journal.ENDING_BALANCE, 0.00) from account_journal WHERE account_journal.ACCOUNT_ID = account.ID  and account_journal.LOCATION_ID = '$LOCATION_ID' order by account_journal.OBJECT_DATE desc, account_journal.ID desc LIMIT 1  ) ";

        return Accounts::query()
            ->select(
                [
                    'account.ID',
                    'account.NAME',
                    'account.GROUP_ACCOUNT_ID',
                    'account.TYPE',
                    'account.BANK_ACCOUNT_NO',
                    'account.INACTIVE',
                    'account.TAG',
                    'account.LINE_NO',
                    'account_type_map.DESCRIPTION as ACCOUNT_TYPE',
                    'g.NAME as GROUP_ACCOUNT',
                    DB::raw("$EB_SQL as ENDING_BALANCE"),
                ]
            )
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->leftJoin('account as g', 'g.ID', '=', 'account.GROUP_ACCOUNT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('account.NAME', 'like', '%' . $search . '%')
                    ->orwhere('account_type_map.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('account.TAG', 'like', '%' . $search . '%');
            })
            ->when(! $showAll, function ($query) {
                $query->where('account.INACTIVE', '=', 0);
            })
            ->orderBy('account.TYPE', 'asc')
            ->paginate(40);
    }
    public function AccountList()
    {
        return Accounts::query()
            ->select(
                [
                    'account.ID',
                ]
            )
            ->orderBy('account.TYPE', 'asc')
            ->get();
    }
    public function IncomeStatementMonthly()
    {
        $result = Accounts::query()
            ->select([
                'account.ID',
                'account.NAME',
                'account.TYPE',
            ])
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->where('account.INACTIVE', '=', 0)
            ->whereIn('account.TYPE', [10, 11, 12, 13, 14])
            ->orderByRaw("FIELD(account.TYPE, '10', '11', '13','12','14')")
            ->get();

        return $result;
    }
    public function BalanceSheetMonthly()
    {
        $result = Accounts::query()
            ->select([
                'account.ID',
                'account.NAME',
                'account.TYPE',
            ])
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->where('account.INACTIVE', '=', 0)
            ->whereIn('account.TYPE', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9])
            ->orderBy("account.TYPE", 'asc')
            ->get();

        return $result;
    }
    public function getAccountNameIntoId(string $Name): int
    {
        $result = Accounts::query()->select('account.ID')->where('NAME', '=', $Name)->first();

        if ($result) {
            return (int) $result->ID ?? 0;
        }
        return 0;

    }
    public static function getAccountNameExist(string $Name): bool
    {
        return (bool) Accounts::where('NAME', '=', $Name)->exists();

    }
}
