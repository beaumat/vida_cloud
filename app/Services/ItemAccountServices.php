<?php

namespace App\Services;

use App\Models\Accounts;
use App\Models\ItemAccounts;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class ItemAccountServices
{
    private $accountServices;
    public function __construct(AccountServices $accountServices)
    {
        $this->accountServices = $accountServices;
    }

    public function Store(int $ITEM_ID, int $ACCOUNT_ID)
    {
        ItemAccounts::create([
            'ITEM_ID' => $ITEM_ID,
            'ACCOUNT_ID'  => $ACCOUNT_ID
        ]);
    }

    public function Delete(int $ITEM_ID, int $ACCOUNT_ID)
    {
        ItemAccounts::where('ITEM_ID', '=', $ITEM_ID)
            ->where('ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->delete();
    }
    public function AccountList(int $ITEM_ID)
    {
        if ($ITEM_ID  == 0) {

            return;
        }

        $result = ItemAccounts::select([
            'a.ID',
            'a.NAME'
        ])
            ->join('account as a', 'a.ID', '=', 'item_accounts.ACCOUNT_ID')
            ->where('a.INACTIVE', '=', false)
            ->where('item_accounts.ITEM_ID', '=', $ITEM_ID);


        if ($result->exists()) {

            return $result->get();
        }

        return $this->accountServices->getIncome();
    }
    public function AccountAvailable($search, int $ITEM_ID)
    {
        $result = accounts::select(['account.ID', 'account.NAME', 'account_type_map.DESCRIPTION as TYPE'])
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->where('account.INACTIVE', '=', false)
            ->whereIn('account.TYPE', ['10', '13'])
            ->whereNotExists(function ($query) use (&$ITEM_ID) {
                $query->select(DB::raw(1))
                    ->from('item_accounts as a')
                    ->whereRaw('a.ACCOUNT_ID = account.ID')
                    ->where('a.ITEM_ID', $ITEM_ID);
            })
            ->when($search, function ($query) use (&$search) {

                $query->where('account.NAME', 'like', '%' . $search . '%');
            })

            ->get();

        return $result;
    }

    public function AccountSelected($search, int $ITEM_ID): object
    {

        $result = accounts::select(['account.ID', 'account.NAME', 'account_type_map.DESCRIPTION as TYPE'])
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->where('account.INACTIVE', '=', false)
            ->whereIn('account.TYPE', ['10', '13'])
            ->whereExists(function ($query) use (&$ITEM_ID) {
                $query->select(DB::raw(1))
                    ->from('item_accounts as a')
                    ->whereRaw('a.ACCOUNT_ID = account.ID')
                    ->where('a.ITEM_ID', $ITEM_ID);
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('account.NAME', 'like', '%' . $search . '%');
            })
            ->get();

        return $result;
    }
}
