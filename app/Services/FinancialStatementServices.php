<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FinancialStatementServices
{
    private function getIncomeStatementAccountByType(string $dateFrom, string $dateTo, int $LOCATION_ID, int $accountType, bool $isCreditIncrease = false): object
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.TYPE', '=', $accountType)
            ->groupBy(['a.NAME'])
            ->get();

        return $result;
    }
    public function getIncomeStatementAccountByTypeSum(string $dateFrom, string $dateTo, int $LOCATION_ID, int $accountType, bool $isCreditIncrease = true): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.TYPE', '=', $accountType)
            ->first()
            ->AMOUNT;

        return (float) $result;
    }
    public static function getIncomeStatementAccountByTypeSumArray(string $dateFrom, string $dateTo, int $LOCATION_ID, array $accountType = [], bool $isCreditIncrease = true): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $accountType)
            ->first()
            ->AMOUNT;

        // Return the result or 0 if no data found
        return (float) $result;
    }
    public static function getIncomeStatementAccountByIDSum(string $dateFrom, string $dateTo, int $LOCATION_ID, int $accountId, bool $isCreditIncrease = false): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.ID', '=', $accountId)
            ->first()
            ->AMOUNT;

        // Return the result or 0 if no data found
        return (float) $result;
    }


    public static function getIncomeStatementAccountByIDSumArray(string $dateFrom, string $dateTo, int $LOCATION_ID, array $accountId = [], bool $isCreditIncrease = false): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.ID', $accountId)
            ->first()
            ->AMOUNT;

        // Return the result or 0 if no data found
        return (float) $result;
    }
    public function IncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): object
    {
        $result = $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            10,
            true
        );

        return $result;
    }
    public function SumIncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            10,
            true
        );
    }
    public function CogsAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        $result = $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            11,
            false
        );

        return $result;
    }
    public function SumCogsAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            11,
            false
        );
    }
    public function ExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            12,
            false
        );
    }
    public function SumExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            12,
            false
        );
    }
    public function OtherIncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            13,
            false
        );
    }
    public function SumOtherIncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            13,
            false
        );
    }
    public function OtherExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            14,
            false
        );
    }
    public function SumOtherExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            14,
            false
        );
    }

    public function getTotalNetIncome(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        // Create a DateTime object from the input date string

        $IncomeSum = $this->SumIncomeAccount($dateFrom, $dateTo, $LOCATION_ID);

        $COGSSum = $this->SumCogsAccount($dateFrom, $dateTo, $LOCATION_ID);

        $gross_profit = $IncomeSum - $COGSSum;

        $ExpenseSum = $this->SumExpensesAccount($dateFrom, $dateTo, $LOCATION_ID);

        $operating_income = $gross_profit - $ExpenseSum;

        $OtherIncomeSum = $this->SumOtherIncomeAccount($dateFrom, $dateTo, $LOCATION_ID);

        $OtherExpenseSum = $this->SumOtherExpensesAccount($dateFrom, $dateTo, $LOCATION_ID);

        $net_other_income = $OtherIncomeSum - $OtherExpenseSum;

        $net_income = $operating_income + $net_other_income;

        return $net_income;
    }
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
    public function getBalanceSheetAccountByAcctID(string $date, int $LOCATION_ID, array $AccountId, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->where('aj.OBJECT_DATE', '<=', $date)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.ID', $AccountId)
            ->groupBy(['a.NAME'])
            ->get();

        return $result;
    }

    public function getBalanceSheetAccountTypeListByMonth(array $type = [], int $YEAR, int $LOCATION_ID, bool $isCreditIncrease = false, bool $isRunning = true, bool $isFocusYear = false)
    {

        $jan = $isRunning == true ? $this->lastDate($YEAR, 1) : '1';
        $feb = $isRunning == true ? $this->lastDate($YEAR, 2) : '2';
        $mar = $isRunning == true ? $this->lastDate($YEAR, 3) : '3';
        $apr = $isRunning == true ? $this->lastDate($YEAR, 4) : '4';
        $may = $isRunning == true ? $this->lastDate($YEAR, 5) : '5';
        $jun = $isRunning == true ? $this->lastDate($YEAR, 6) : '6';
        $jul = $isRunning == true ? $this->lastDate($YEAR, 7) : '7';
        $aug = $isRunning == true ? $this->lastDate($YEAR, 8) : '8';
        $sep = $isRunning == true ? $this->lastDate($YEAR, 9) : '9';
        $oct = $isRunning == true ? $this->lastDate($YEAR, 10) : '10';
        $nov = $isRunning == true ? $this->lastDate($YEAR, 11) : '11';
        $dec = $isRunning == true ? $this->lastDate($YEAR, 12) : '12';



        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $operation = $isRunning == true ? "<=" : "=";
        $monthLabel = $isRunning == true ? "aj.OBJECT_DATE" : "MONTH(aj.OBJECT_DATE)";
        $sqlENntry = "if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)";

        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$jan' THEN  $sqlENntry ELSE 0 END) as JAN"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$feb' THEN  $sqlENntry ELSE 0 END) as FEB"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$mar' THEN  $sqlENntry ELSE 0 END) as MAR"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$apr' THEN  $sqlENntry ELSE 0 END) as APR"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$may' THEN  $sqlENntry ELSE 0 END) as MAY"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$jun' THEN  $sqlENntry ELSE 0 END) as JUN"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$jul' THEN  $sqlENntry ELSE 0 END) as JUL"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$aug' THEN  $sqlENntry ELSE 0 END) as AUG"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$sep' THEN  $sqlENntry ELSE 0 END) as SEP"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$oct' THEN  $sqlENntry ELSE 0 END) as OCT"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$nov' THEN  $sqlENntry ELSE 0 END) as NOV"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$dec' THEN  $sqlENntry ELSE 0 END) as `DEC`"),
                DB::raw("SUM( $sqlENntry) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $type)
            ->when(!$isFocusYear, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '<=', $YEAR);
            })
            ->when($isFocusYear, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '=', $YEAR);
            })
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
            ->orderBy('a.TYPE')
            ->get();

           
        return $result;
    }
    public function getBalanceSheetAccountTypeListByDateRange(array $type = [], string $DATE_FROM, string $DATE_TO, int $LOCATION_ID, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $type)
            ->whereBetween('aj.OBJECT_DATE', [$DATE_FROM, $DATE_TO])
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
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


    public function getBalanceSheetAccountTypeListByHistory(array $type = [], string $DATE, int $LOCATION_ID, bool $isCreditIncrease = false): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $result = (float) DB::table('account_journal as aj')
            ->select([
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $type)
            ->where('aj.OBJECT_DATE', '<=', $DATE)
            ->first()
            ->TOTAL;

        return $result;
    }
    public function getBalanceSheetBalance(string $Date, int $LOCATION_ID)
    {


        $assetAmount = $this->getBalanceSheetAccountTypeListByHistory([0, 1, 2, 3, 4], $Date, $LOCATION_ID, false);
        $liabilityAmount = $this->getBalanceSheetAccountTypeListByHistory([5, 6, 7, 8], $Date, $LOCATION_ID, true);
        $TOTAL = $assetAmount - $liabilityAmount;
        $equtty = $this->getBalanceSheetAccountTypeListByHistory([9], $Date, $LOCATION_ID, true);

        return $TOTAL;
    }

    public function getIncomeStatementHistoryBalance(string $date, int $LOCATION_ID): float
    {


        $INCOME_AMOUNT = $this->getIncomeStatementAccountTypeByHistory([10], $date, $LOCATION_ID, true);
        $COGS_AMOUNT = $this->getIncomeStatementAccountTypeByHistory([11], $date, $LOCATION_ID, false);
        $OTHERINCOME_TOTAL = $this->getIncomeStatementAccountTypeByHistory([13], $date, $LOCATION_ID, true);
        $EXPENSE_TOTAL = $this->getIncomeStatementAccountTypeByHistory([12], $date, $LOCATION_ID, false);
        $OTHEREXPENSE_TOTAL = $this->getIncomeStatementAccountTypeByHistory([14], $date, $LOCATION_ID, true);


        $G_TOTAL = $INCOME_AMOUNT - $COGS_AMOUNT;

        $OP_TOTAL = $G_TOTAL - $EXPENSE_TOTAL;


        $NET_TOTAL = $OP_TOTAL + $OTHERINCOME_TOTAL - $OTHEREXPENSE_TOTAL;

        return $NET_TOTAL;

    }
    public function lastDate(int $year, int $month): string
    {
        return date("Y-m-t", strtotime("$year-$month-01"));
    }
    public function getIncomeStatementAccountTypeByMonth(array $TYPE = [], int $YEAR, int $LOCATION_ID, bool $isCreditIncrease = false, bool $isRunning = true, bool $focusThisYear = false): object
    {

        $jan = $isRunning == true ? $this->lastDate($YEAR, 1) : '1';
        $feb = $isRunning == true ? $this->lastDate($YEAR, 2) : '2';
        $mar = $isRunning == true ? $this->lastDate($YEAR, 3) : '3';
        $apr = $isRunning == true ? $this->lastDate($YEAR, 4) : '4';
        $may = $isRunning == true ? $this->lastDate($YEAR, 5) : '5';
        $jun = $isRunning == true ? $this->lastDate($YEAR, 6) : '6';
        $jul = $isRunning == true ? $this->lastDate($YEAR, 7) : '7';
        $aug = $isRunning == true ? $this->lastDate($YEAR, 8) : '8';
        $sep = $isRunning == true ? $this->lastDate($YEAR, 9) : '9';
        $oct = $isRunning == true ? $this->lastDate($YEAR, 10) : '10';
        $nov = $isRunning == true ? $this->lastDate($YEAR, 11) : '11';
        $dec = $isRunning == true ? $this->lastDate($YEAR, 12) : '12';

        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $operation = $isRunning == true ? "<=" : "=";
        $monthLabel = $isRunning == true ? "aj.OBJECT_DATE" : "MONTH(aj.OBJECT_DATE)";
        $sqlENntry = "if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$jan' THEN $sqlENntry ELSE 0 END) as JAN"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$feb' THEN $sqlENntry ELSE 0 END) as FEB"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$mar' THEN $sqlENntry ELSE 0 END) as MAR"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$apr' THEN $sqlENntry ELSE 0 END) as APR"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$may' THEN $sqlENntry ELSE 0 END) as MAY"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$jun' THEN $sqlENntry ELSE 0 END) as JUN"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$jul' THEN $sqlENntry ELSE 0 END) as JUL"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$aug' THEN $sqlENntry ELSE 0 END) as AUG"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$sep' THEN $sqlENntry ELSE 0 END) as SEP"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$oct' THEN $sqlENntry ELSE 0 END) as OCT"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$nov' THEN $sqlENntry ELSE 0 END) as NOV"),
                DB::raw("SUM(CASE WHEN $monthLabel $operation '$dec' THEN $sqlENntry ELSE 0 END) as `DEC`"),
                DB::raw("SUM($sqlENntry) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $TYPE)
            ->when(!$focusThisYear, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '<=', $YEAR);
            })
            ->when($focusThisYear, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '=', $YEAR);
            })
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    public function getIncomeStatementAccountTypeByDate(array $TYPE = [], string $dateFrom, string $dateTo, int $LOCATION_ID, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $TYPE)
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    public function getIncomeStatementAccountTypeByHistory(array $TYPE = [], string $DATE, int $LOCATION_ID, bool $isCreditIncrease = false): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $result = (float) DB::table('account_journal as aj')
            ->select([
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $TYPE)
            ->where('aj.OBJECT_DATE', '<=', $DATE)
            ->first()
            ->TOTAL;

        return $result;
    }
}
