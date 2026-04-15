<?php
namespace App\Services;

use App\Models\Contacts;
use Illuminate\Support\Facades\DB;

class StatementServices
{
    public function CustomerSoaEntryList(int $CONTACT_ID, string $DATE_FROM, string $DATE_TO = '')
    {

        $invoice = DB::table('invoice as i')
            ->select(
                'i.CUSTOMER_ID',
                DB::raw("'INVOICE' as TYPE"),
                DB::raw("0 as ENTRY_TYPE"),
                'i.CODE',
                'i.ID',
                'i.DATE',
                'i.AMOUNT',
                DB::raw('(i.AMOUNT) as AMT'),
                'i.LOCATION_ID',
                DB::raw("(SELECT GROUP_CONCAT(item.`DESCRIPTION` SEPARATOR ', ') FROM invoice_items  INNER JOIN  item ON item.id = invoice_items.`ITEM_ID` WHERE invoice_items.`INVOICE_ID` = i.`ID`) AS DESCRIPTION")
            )->where('i.STATUS', '=', 15);

        $payment = DB::table('payment as p')
            ->select(
                'p.CUSTOMER_ID',
                DB::raw("'PAYMENT' as TYPE"),
                DB::raw("1 as ENTRY_TYPE"),
                'p.CODE',
                'p.ID',
                'p.DATE',
                'p.AMOUNT',
                DB::raw('(p.AMOUNT * -1) as AMT '),
                'p.LOCATION_ID',
                DB::raw('(select pm.DESCRIPTION from PAYMENT_METHOD as pm WHERE pm.ID = p.PAYMENT_METHOD_ID ) as DESCRIPTION')
            )->where('p.STATUS', '=', 15);

        $creditMemo = DB::table('credit_memo as c')
            ->select(
                'c.CUSTOMER_ID',
                DB::raw("'CREDIT MEMO' as TYPE"),
                DB::raw("1 as ENTRY_TYPE"),
                'c.CODE',
                'c.ID',
                'c.DATE',
                'c.AMOUNT',
                DB::raw('(c.AMOUNT * -1) as AMT '),
                'c.LOCATION_ID',
                DB::raw("( SELECT GROUP_CONCAT(item.`DESCRIPTION` SEPARATOR ', ') FROM credit_memo_items INNER JOIN item ON item.id = credit_memo_items.`ITEM_ID` WHERE credit_memo_items.`CREDIT_MEMO_ID` = c.`ID` ) AS DESCRIPTION")
            )
            ->where('c.STATUS', '=', 15);

        $taxCredit = DB::table('tax_credit as x')
            ->select(
                'x.CUSTOMER_ID',
                DB::raw("'TAX CREDIT' as TYPE"),
                DB::raw("1 as ENTRY_TYPE"),
                'x.CODE',
                'x.ID',
                'x.DATE',
                'x.AMOUNT',
                DB::raw('(x.AMOUNT * -1) as AMT '),
                'x.LOCATION_ID',
                DB::raw('(SELECT TAX.NAME FROM TAX WHERE TAX.ID = x.EWT_ID) as DESCRIPTION')
            )->where('x.STATUS', '=', 15);

        $query = $invoice
            ->unionAll($payment)
            ->unionAll($creditMemo)
            ->unionAll($taxCredit);

        $results = DB::query()
            ->fromSub($query, 'BAL')
            ->select('BAL.*', 'l.NAME as LOCATION') // include all BAL fields + location name
            ->join('location as l', 'BAL.LOCATION_ID', '=', 'l.ID')
            ->where('BAL.CUSTOMER_ID', $CONTACT_ID)
            ->when($DATE_TO == '', function ($query) use (&$DATE_FROM) {
                $query->where('BAL.DATE', '<=', $DATE_FROM);
            })
            ->when($DATE_TO != '', function ($query) use (&$DATE_FROM, &$DATE_TO) {
                $query->whereBetween('BAL.DATE', [$DATE_FROM, $DATE_TO]);
            })
            ->orderBy('BAL.DATE', 'ASC')
            ->get();

        return $results;
    }
    public function CustomerSoaBalance(int $CONTACT_ID, string $DATE_FROM, string $DATE_TO, $IS_ENTRY = null): float
    {

        $invoice = DB::table('invoice as i')
            ->select(
                'i.CUSTOMER_ID',
                DB::raw("'INVOICE' as TYPE"),
                DB::raw("0 as ENTRY_TYPE"),
                'i.CODE',
                'i.ID',
                'i.DATE',
                'i.AMOUNT',
                DB::raw('(i.AMOUNT) as AMT'),
                'i.LOCATION_ID',
                DB::raw("(SELECT GROUP_CONCAT(item.`DESCRIPTION` SEPARATOR ', ') FROM invoice_items  INNER JOIN  item ON item.id = invoice_items.`ITEM_ID` WHERE invoice_items.`INVOICE_ID` = i.`ID`) AS DESCRIPTION")
            )->where('i.STATUS', '=', 15);

        $payment = DB::table('payment as p')
            ->select(
                'p.CUSTOMER_ID',
                DB::raw("'PAYMENT' as TYPE"),
                DB::raw("1 as ENTRY_TYPE"),
                'p.CODE',
                'p.ID',
                'p.DATE',
                'p.AMOUNT',
                DB::raw('(p.AMOUNT * -1) as AMT '),
                'p.LOCATION_ID',
                DB::raw('(select pm.DESCRIPTION from PAYMENT_METHOD as pm WHERE pm.ID = p.PAYMENT_METHOD_ID ) as DESCRIPTION')
            )->where('p.STATUS', '=', 15);

        $creditMemo = DB::table('credit_memo as c')
            ->select(
                'c.CUSTOMER_ID',
                DB::raw("'CREDIT MEMO' as TYPE"),
                DB::raw("1 as ENTRY_TYPE"),
                'c.CODE',
                'c.ID',
                'c.DATE',
                'c.AMOUNT',
                DB::raw('(c.AMOUNT * -1) as AMT '),
                'c.LOCATION_ID',
                DB::raw("( SELECT GROUP_CONCAT(item.`DESCRIPTION` SEPARATOR ', ') FROM credit_memo_items INNER JOIN item ON item.id = credit_memo_items.`ITEM_ID` WHERE credit_memo_items.`CREDIT_MEMO_ID` = c.`ID` ) AS DESCRIPTION")
            )
            ->where('c.STATUS', '=', 15);

        $taxCredit = DB::table('tax_credit as x')
            ->select(
                'x.CUSTOMER_ID',
                DB::raw("'TAX CREDIT' as TYPE"),
                DB::raw("1 as ENTRY_TYPE"),
                'x.CODE',
                'x.ID',
                'x.DATE',
                'x.AMOUNT',
                DB::raw('(x.AMOUNT * -1) as AMT '),
                'x.LOCATION_ID',
                DB::raw('(SELECT TAX.NAME FROM TAX WHERE TAX.ID = x.EWT_ID) as DESCRIPTION')
            )->where('x.STATUS', '=', 15);

        $query = $invoice
            ->unionAll($payment)
            ->unionAll($creditMemo)
            ->unionAll($taxCredit);

        $results = DB::query()
            ->fromSub($query, 'BAL')
            ->join('location as l', 'BAL.LOCATION_ID', '=', 'l.ID')
            ->where('BAL.CUSTOMER_ID', $CONTACT_ID)

            ->when($DATE_TO == '', function ($query) use (&$DATE_FROM) {
                $query->where('BAL.DATE', '<=', $DATE_FROM);
            })
            ->when($DATE_TO != '', function ($query) use (&$DATE_FROM, &$DATE_TO) {
                $query->whereBetween('BAL.DATE', [$DATE_FROM, $DATE_TO]);
            })
            ->when(is_numeric($IS_ENTRY) == true, function ($query) use ($IS_ENTRY) {
                $query->WHERE('BAL.ENTRY_TYPE', '=', $IS_ENTRY);
            })
            ->orderBy('BAL.DATE', 'ASC')
            ->sum('AMT');

        return (float) $results;
    }
    public function CustomerSoaList(string $AS_OF_DATE, string $search, bool $ShowBalanceOnly = true)
    {
        $BALANCE_SQL = "(select SUM(AMT)  from (
	(select i.`CUSTOMER_ID`,'INVOICE' as `TYPE`, 0 as  ENTRY_TYPE,i.`CODE`,i.`ID`, i.`DATE`,i.`AMOUNT`,(i.AMOUNT) as AMT from invoice as i WHERE i.STATUS = 15 )
		UNION ALL
	( select p.CUSTOMER_ID,'PAYMENT' as `TYPE`,1 as  ENTRY_TYPE, p.CODE,p.ID, p.DATE, p.AMOUNT,(p.AMOUNT * -1) as AMT from PAYMENT as p WHERE p.STATUS = 15)
		UNION ALL
	(SELECT c.CUSTOMER_ID,'CREDIT MEMO' as `TYPE`,1 as  ENTRY_TYPE,c.CODE, c.ID, c.DATE, c.AMOUNT,(c.AMOUNT * -1) as AMT FROM CREDIT_MEMO as c WHERE c.STATUS = 15)
        UNION ALL
	( select x.CUSTOMER_ID,'TAX CREDIT' as `TYPE`, 1 as  ENTRY_TYPE, x.CODE,x.ID, x.DATE, x.AMOUNT,(x.AMOUNT * -1) as AMT from TAX_CREDIT as x WHERE x.STATUS = 15)
) as BAL WHERE BAL.CUSTOMER_ID = contact.ID and BAL.DATE <='$AS_OF_DATE')  ";

        $result = Contacts::fromSub(function ($sub) use ($BALANCE_SQL) {
            $sub->from('contact')
                ->select([
                    'contact.ID',
                    'contact.NAME',
                    'contact.FIRST_NAME',
                    'contact.LAST_NAME',
                    'contact.MIDDLE_NAME',
                    't.DESCRIPTION as TYPE',
                    DB::raw($BALANCE_SQL . ' as BALANCE'),
                ])
                ->join('contact_type_map as t', 't.ID', '=', 'contact.TYPE')
                ->whereIn('contact.TYPE', [1, 3])
                ->where('contact.INACTIVE', '0');
        }, 'sub')
            ->when($ShowBalanceOnly, fn($q) => $q->where('BALANCE', '>', 0))
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('LAST_NAME', 'like', "%" . $search . "%")
                        ->orWhere('FIRST_NAME', 'like', "%" . $search . "%")
                        ->orWhere('MIDDLE_NAME', 'like', "%" . $search . "%")
                        ->orWhere('NAME', 'like', "%" . $search . "%");
                });
            })
            ->paginate(100);

        return $result;
    }

}
