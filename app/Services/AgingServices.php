<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class AgingServices
{
    public function __construct()
    {
    }

    public function ARAgingSummary(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('contact as c')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                't.DESCRIPTION as TYPE',
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) <= 0 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_CURRENT "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 1 AND 30 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_1_30 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 31 AND 60 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_31_60 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 61 AND 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_61_90 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) > 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_90_OVER "),
                DB::raw(" SUM(i.BALANCE_DUE) AS BALANCE"),
            ])
            ->join('contact_type_map as t', 't.ID', '=', 'c.TYPE')
            ->leftJoin('invoice as i', function ($join) {
                $join->on('i.CUSTOMER_ID', '=', 'c.ID');
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->whereIn('c.TYPE', [1, 3])
            ->groupBy('c.ID', 'c.NAME', 't.DESCRIPTION')
            ->where('i.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }
    public function ARAgingDetais(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        // $result = DB::table('invoice as i')
        //     ->select([
        //         'c.ID as CONTACT_ID',
        //         'c.NAME as CONTACT_NAME',
        //         'ct.DESCRIPTION as TYPE',
        //         DB::raw(" DATEDIFF('$AS_OF_DATE',i.DUE_DATE) as AGING"),
        //         'i.DATE',
        //         'i.CODE',
        //         'i.DUE_DATE',
        //         'i.AMOUNT',
        //         'i.BALANCE_DUE',
        //         't.DESCRIPTION as PAYMENT_TERMS',
        //         'l.NAME as LOCATION_NAME',

        //     ])
        //     ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
        //     ->join('contact_type_map as ct', 'ct.ID', '=', 'c.TYPE')
        //     ->join('payment_terms as t', 't.ID', '=', 'i.PAYMENT_TERMS_ID')
        //     ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
        //     ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
        //         $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
        //     })
        //     ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
        //         $query->whereIn('c.ID', $CONTACT_SELECT);
        //     })
        //     ->whereIn('c.TYPE', [1, 3])
        //     ->where('i.BALANCE_DUE', '>', 0)
        //     ->groupBy('c.NAME', 't.DESCRIPTION', 'l.NAME')
        //     ->orderBy('DUE_DATE', 'desc')
        //     ->get();

        $result = DB::table('invoice as i')
    ->select([
        'c.ID as CONTACT_ID',
        'c.NAME as CONTACT_NAME',
        'ct.DESCRIPTION as TYPE',
        DB::raw("DATEDIFF('$AS_OF_DATE', i.DUE_DATE) as AGING"),
        'i.DATE',
        'i.CODE',
        'i.DUE_DATE',
        'i.AMOUNT',
        'i.BALANCE_DUE',
        't.DESCRIPTION as PAYMENT_TERMS',
        'l.NAME as LOCATION_NAME',
    ])
    ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
    ->join('contact_type_map as ct', 'ct.ID', '=', 'c.TYPE')
    ->join('payment_terms as t', 't.ID', '=', 'i.PAYMENT_TERMS_ID')
    ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
    ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
        $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
    })
    ->when($CONTACT_SELECT, function ($query) use ($CONTACT_SELECT) {
        $query->whereIn('c.ID', $CONTACT_SELECT);
    })
    ->whereIn('c.TYPE', [1, 3])
    ->where('i.BALANCE_DUE', '>', 0)
    ->orderBy('c.NAME', 'asc')
    ->get();

        return $result;
    }
    public function CustomerBalance(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('invoice as i')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                't.DESCRIPTION as TYPE',
                'l.NAME as LOCATION_NAME',

                DB::raw('SUM(i.BALANCE_DUE) as BALANCE'),
            ])
            ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
            ->join('contact_type_map as t', 't.ID', '=', 'c.TYPE')
            ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->where('i.DATE', '<=', $AS_OF_DATE)
            ->whereIn('c.TYPE', [1, 3])
            ->where('i.BALANCE_DUE', '>', 0)
            ->groupBy('c.ID', 'c.NAME', 't.DESCRIPTION', 'l.NAME')
            ->orderBy('c.NAME', 'asc')
            ->get();

        return $result;
    }
    public function CustomerBalanceDetails(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('invoice as i')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                't.DESCRIPTION as TYPE',
                'l.NAME as LOCATION_NAME',
                'i.BALANCE_DUE as BALANCE',
                'i.DATE',
                'i.CODE',
                'i.DUE_DATE',
                'pt.DESCRIPTION as TERMS',
                'i.ID as INVOICE_ID',
            ])
            ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
            ->join('contact_type_map as t', 't.ID', '=', 'c.TYPE')
            ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
            ->join('payment_terms as pt', 'pt.ID', '=', 'i.PAYMENT_TERMS_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->where('i.DATE', '<=', $AS_OF_DATE)
            ->whereIn('c.TYPE', [1, 3])
            ->where('i.BALANCE_DUE', '>', 0)
            ->orderBy('c.NAME', 'asc')
            ->orderBy('i.DATE', 'asc')
            ->get();

        return $result;
    }
    public function CustomerBalanceByRange(string $DATE_FROM, string $DATE_TO, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('invoice as i')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                't.DESCRIPTION as TYPE',
                'l.NAME as LOCATION_NAME',

                DB::raw('SUM(i.BALANCE_DUE) as BALANCE'),
            ])
            ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
            ->join('contact_type_map as t', 't.ID', '=', 'c.TYPE')
            ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->whereBetween('i.DATE', [$DATE_FROM, $DATE_TO])
            ->whereIn('c.TYPE', [1, 3])
            ->where('i.BALANCE_DUE', '>', 0)
            ->groupBy('c.ID', 'c.NAME', 't.DESCRIPTION', 'l.NAME')
            ->orderBy('c.NAME', 'asc')
            ->get();

        return $result;
    }
    public function CustomerBalanceDetailsByRange(string $DATE_FROM, string $DATE_TO, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('invoice as i')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                't.DESCRIPTION as TYPE',
                'l.NAME as LOCATION_NAME',
                'i.BALANCE_DUE as BALANCE',
                'i.DATE',
                'i.CODE',
                'i.DUE_DATE',
                'pt.DESCRIPTION as TERMS',
                'i.ID as INVOICE_ID',
            ])
            ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
            ->join('contact_type_map as t', 't.ID', '=', 'c.TYPE')
            ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
            ->join('payment_terms as pt', 'pt.ID', '=', 'i.PAYMENT_TERMS_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->whereBetween('i.DATE', [$DATE_FROM, $DATE_TO])
            ->whereIn('c.TYPE', [1, 3])
            ->where('i.BALANCE_DUE', '>', 0)
            ->orderBy('c.NAME', 'asc')
            ->orderBy('i.DATE', 'asc')
            ->get();

        return $result;
    }
    public function APAgingSummary(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('contact as c')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                't.DESCRIPTION as TYPE',
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) <= 0 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_CURRENT "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 1 AND 30 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_1_30 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 31 AND 60 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_31_60 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 61 AND 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_61_90 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) > 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_90_OVER "),
                DB::raw(" SUM(i.BALANCE_DUE) AS BALANCE"),
            ])
            ->join('contact_type_map as t', 't.ID', '=', 'c.TYPE')
            ->leftJoin('bill as i', function ($join) {
                $join->on('i.VENDOR_ID', '=', 'c.ID');
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })

            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->whereIn('c.TYPE', [0, 4])
            ->groupBy('c.ID', 'c.NAME', 't.DESCRIPTION')
            ->where('i.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }
    public function APAgingDetais(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('bill as i')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                'ct.DESCRIPTION as TYPE',
                DB::raw(" DATEDIFF('$AS_OF_DATE',i.DUE_DATE) as AGING"),
                'i.DATE',
                'i.CODE',
                'i.DUE_DATE',
                'i.AMOUNT',
                'i.BALANCE_DUE',
                't.DESCRIPTION as PAYMENT_TERMS',
                'l.NAME as LOCATION_NAME',

            ])
            ->join('contact as c', 'c.ID', '=', 'i.VENDOR_ID')
            ->join('contact_type_map as ct', 'ct.ID', '=', 'c.TYPE')
            ->join('payment_terms as t', 't.ID', '=', 'i.PAYMENT_TERMS_ID')
            ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })

            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->whereIn('c.TYPE', [0, 4])
            ->where('i.BALANCE_DUE', '>', 0)
            ->orderBy('DUE_DATE', 'desc')
            ->get();

        return $result;
    }
    public function VendorBalance(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('bill as i')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                't.DESCRIPTION as TYPE',
                'l.NAME as LOCATION_NAME',
                DB::raw('SUM(i.BALANCE_DUE) as BALANCE'),
            ])
            ->join('contact as c', 'c.ID', '=', 'i.VENDOR_ID')
            ->join('contact_type_map as t', 't.ID', '=', 'c.TYPE')
            ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->where('i.DATE', '<=', $AS_OF_DATE)
            ->whereIn('c.TYPE', [0, 4])
            ->where('i.BALANCE_DUE', '>', 0)
            ->groupBy('c.ID', 'c.NAME', 't.DESCRIPTION', 'l.NAME')
            ->orderBy('c.NAME', 'asc')
            ->get();

        return $result;
    }
}
