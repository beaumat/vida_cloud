<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PettyCashService
{
    
    // Balance Sheet
    public function getBalanceSheetAccountByAcctType(string $dateFrom, string $dateTo, int $LOCATION_ID, array $AccountType, bool $isCreditIncrease = false, array $NotIncludeAccntID = []): object
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";

        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',
                DB::raw($sql),
                'at.DESCRIPTION as ACCOUNT_TYPE',
                'at.ACCOUNT_ORDER as ORDER'
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as at', 'at.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $AccountType)
            ->whereNotIn('a.ID', $NotIncludeAccntID)
            ->groupBy(['a.NAME', 'at.DESCRIPTION', 'at.ACCOUNT_ORDER'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    

    // /// PETTY CASH
   public function getPettyCashListByDateRange(
    array $type = [],
    string $DATE_FROM,
    string $DATE_TO,
    int $LOCATION_ID,
    bool $isCreditIncrease = false
) {
    $result = DB::table('bill')
        ->select([
            'bill.ID',
            'bill.CODE',
            'bill.DATE',
            'bill.AMOUNT',
            'bill.BALANCE_DUE',
            'bill.INPUT_TAX_RATE',
            'bill.NOTES',
            'c.NAME as CONTACT_NAME',
            'l.NAME as LOCATION_NAME',
            't.NAME as TAX_NAME',
            's.DESCRIPTION as STATUS',
            'bill.STATUS as STATUS_ID',
        ])
        ->join('contact as c', 'c.ID', '=', 'bill.VENDOR_ID')
        ->join('location as l', 'l.ID', '=', 'bill.LOCATION_ID')
        ->join('document_status_map as s', 's.ID', '=', 'bill.STATUS')
        ->leftJoin('tax as t', 't.ID', '=', 'bill.INPUT_TAX_ID')

        // ✅ Filters (based on your SQL)
        ->where('c.ID', '=', 1225) // you can replace with variable if needed
        ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
            return $query->where('bill.LOCATION_ID', '=', $LOCATION_ID);
        })
        ->whereBetween('bill.DATE', [$DATE_FROM, $DATE_TO])
        ->where('bill.STATUS', '=', 15)

        ->get();

    return $result;
}


   
}
