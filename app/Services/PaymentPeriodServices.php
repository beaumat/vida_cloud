<?php
namespace App\Services;

use App\Models\PaymentPeriod;
use Illuminate\Support\Facades\DB;

class PaymentPeriodServices
{

    private $object;

    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function Get($ID)
    {

        $result = PaymentPeriod::where('ID', '=', $ID)->first();

        return $result;
    }
    public function GetData(int $LOCATION_ID, $DATE_FROM, $DATE_TO): object
    {
        $data = PaymentPeriod::query()
            ->select([
                'ID',
                'RECEIPT_NO',
                'DATE_FROM',
                'DATE_TO',
                'DATE',
            ])
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->when($DATE_FROM, function ($query) use (&$DATE_FROM) {
                $query->where('DATE_FROM', '>=', $DATE_FROM);
            })
            ->when($DATE_TO, function ($query) use (&$DATE_TO) {
                $query->where('DATE_TO', '<=', $DATE_TO);
            })
            ->orderBy('ID', 'asc')
            ->get();

        return $data;
    }
    public function GetYear(int $YEAR, int $LOCATION_ID, array $PAYMENT_PERIOD = []): object
    {
        $data = PaymentPeriod::query()
            ->select([
                'ID',
                'RECEIPT_NO',
                'DATE_FROM',
                'DATE_TO',
                'DATE',
            ])
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('DATE_FROM', '=', $YEAR)
            ->whereYear('DATE_TO', '=', $YEAR)
            ->when($PAYMENT_PERIOD, function ($query) use (&$PAYMENT_PERIOD) {
                $query->whereIn('ID', $PAYMENT_PERIOD);
            })
            ->orderBy('ID', 'asc')
            ->get();

        return $data;
    }
    public function Store(
        string $RECEIPT_NO,
        int $LOCATION_ID,
        string $DATE_FROM,
        string $DATE_TO,
        float $TOTAL_PAYMENT,
        float $TOTAL_WTAX,
        int $BANK_ACCOUNT_ID,
        string $DATE
    ) {

        $ID = (int) $this->object->ObjectNextID(TABLE_NAME: 'PAYMENT_PERIOD');
        PaymentPeriod::create([
            'ID'              => $ID,
            'RECEIPT_NO'      => $RECEIPT_NO,
            'LOCATION_ID'     => $LOCATION_ID,
            'DATE_FROM'       => $DATE_FROM,
            'DATE_TO'         => $DATE_TO,
            'TOTAL_PAYMENT'   => $TOTAL_PAYMENT,
            'TOTAL_WTAX'      => $TOTAL_WTAX,
            'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
            'DATE'            => $DATE,
        ]);
    }
    public function dateExists(int $ID, string $DATE): bool
    {

        return PaymentPeriod::where('ID', '=', $ID)->where('DATE', '=', $DATE)->exists();
    }
    public function orNumberExists(int $ID, string $RECEIPT_NO): bool
    {
        return PaymentPeriod::where('ID', '=', $ID)->where('RECEIPT_NO', '=', $RECEIPT_NO)->exists();
    }
    public function bankAccountExists(int $ID, int $BANK_ACCOUNT_ID): bool
    {
        return (bool) PaymentPeriod::where('ID', '=', $ID)->where('BANK_ACCOUNT_ID', '=', $BANK_ACCOUNT_ID)->exists();
    }
    public function Update(int $ID, string $RECEIPT_NO, string $DATE_FROM, string $DATE_TO, string $DATE, float $TOTAL_PAYMENT, int $BANK_ACCOUNT_ID)
    {
        PaymentPeriod::where('ID', '=', $ID)
            ->update([
                'RECEIPT_NO'      => $RECEIPT_NO,
                'DATE_FROM'       => $DATE_FROM,
                'DATE_TO'         => $DATE_TO,
                'DATE'            => $DATE,
                'TOTAL_PAYMENT'   => $TOTAL_PAYMENT,
                'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
            ]);
    }
    public function Delete(int $ID)
    {
        PaymentPeriod::where('ID', '=', $ID)->delete();
    }
    public function search($search, int $locationId, int $perPage)
    {
        $result = PaymentPeriod::query()
            ->select([
                'payment_period.ID',
                'payment_period.RECEIPT_NO',
                'payment_period.DATE_FROM',
                'payment_period.DATE_TO',
                'payment_period.DATE',
                'TOTAL_PAYMENT as AMOUNT',
                'l.NAME as LOCATION_NAME',
                'a.NAME as BANK_ACCOUNT_NAME',
            ])
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'payment_period.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->leftJoin('account as a', 'a.ID', '=', 'payment_period.BANK_ACCOUNT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('payment_period.RECEIPT_NO', 'like', '%' . $search . '%');
            })
            ->orderBy('payment_period.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function list(int $LOCATION_ID)
    {
        $result = PaymentPeriod::query()
            ->select([
                'ID',
                'RECEIPT_NO',
                'DATE_FROM',
                'DATE_TO',
                'DATE',
            ])
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->get();

        return $result;
    }
    public function prPFList(int $LOCATION_ID)
    {
        $result = PaymentPeriod::query()
            ->select([
                'ID',
                DB::raw("CONCAT(DATE_FROM,'-', DATE_TO,'  OR#:',RECEIPT_NO) as NAME"),

            ])
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->get();

        return $result;
    }
    public function GetDropDownList(int $LOCATION_ID)
    {
        $result = PaymentPeriod::query()
            ->select([
                'ID',
                DB::raw("CONCAT('#',RECEIPT_NO,' Date:', DATE_FORMAT(DATE,'%b/%d/%Y')  ,'  period:[',DATE_FORMAT(DATE_FROM,'%b %d, %Y'),'-',DATE_FORMAT(DATE_TO,'%b %d, %Y'),'] ' ) as DESCRIPTION"),

            ])
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->get();

        return $result;
    }
    public function getDoctorByYearPeriod(int $LOCATION_ID, int $YEAR)
    {
        $result = PaymentPeriod::query()
            ->select(
                [
                    'c.ID as DOCTOR_ID',
                    'c.NAME as DOCTOR_NAME',
                ]
            )
            ->join('payment as p', 'p.PAYMENT_PERIOD_ID', '=', 'payment_period.ID')
            ->join('payment_invoices as pn', 'pn.PAYMENT_ID', '=', 'p.ID')
            ->join('invoice as i', 'i.ID', '=', 'pn.INVOICE_ID')
            ->join('philhealth as ph', 'ph.INVOICE_ID', '=', 'i.ID')
            ->join('philhealth_prof_fee as pf', 'pf.PHIC_ID', '=', 'ph.ID')
            ->join('bill as b', 'b.ID', '=', 'pf.BILL_ID')
            ->join('contact as c', 'c.ID', '=', 'b.VENDOR_ID')
            ->where('payment_period.LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('payment_period.DATE_FROM', '=', $YEAR)
            ->whereYear('payment_period.DATE_TO', '=', $YEAR)
            ->groupBy('c.ID', 'c.NAME')
            ->get();

        return $result;
    }
    public function getDoctorByDatePeriod(int $LOCATION_ID, $DATE_FROM, $DATE_TO): object
    {
        $result = PaymentPeriod::query()
            ->select(
                [
                    'c.ID as DOCTOR_ID',
                    'c.NAME as DOCTOR_NAME',
                ]
            )
            ->join('payment as p', 'p.PAYMENT_PERIOD_ID', '=', 'payment_period.ID')
            ->join('payment_invoices as pn', 'pn.PAYMENT_ID', '=', 'p.ID')
            ->join('invoice as i', 'i.ID', '=', 'pn.INVOICE_ID')
            ->join('philhealth as ph', 'ph.INVOICE_ID', '=', 'i.ID')
            ->join('philhealth_prof_fee as pf', 'pf.PHIC_ID', '=', 'ph.ID')
            ->join('bill as b', 'b.ID', '=', 'pf.BILL_ID')
            ->join('contact as c', 'c.ID', '=', 'b.VENDOR_ID')
            ->where('payment_period.LOCATION_ID', '=', $LOCATION_ID)
            ->when($DATE_FROM, function ($query) use (&$DATE_FROM) {
                $query->where('payment_period.DATE_FROM', '>=', $DATE_FROM);
            })
            ->when($DATE_TO, function ($query) use (&$DATE_TO) {
                $query->where('payment_period.DATE_TO', '<=', $DATE_TO);
            })
            ->groupBy('c.ID', 'c.NAME')
            ->get();

        return $result;
    }
    public function getPeriodbyYear(int $LOCATION_ID, int $YEAR)
    {
        $result = PaymentPeriod::query()
            ->select(
                [
                    'ID',
                    DB::raw("CONCAT(RECEIPT_NO,' [', DATE_FORMAT(DATE_FROM,'%b %d'),' - ', DATE_FORMAT(DATE_TO,'%b %d'),'] '  )  as DESCRIPTION"),
                ]
            )
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('DATE_FROM', '=', $YEAR)
            ->whereYear('DATE_TO', '=', $YEAR)
            ->get();

        return $result;
    }
    public function getDoctorFeeTotal(int $LOCATION_ID, int $PAYMENT_PERIOD_ID, int $DOCTOR_ID)
    {
        $result = PaymentPeriod::query()
            ->select(
                [
                    DB::raw('IFNULL(SUM(b.AMOUNT),0) as TOTAL'),
                ]
            )
            ->join('payment as p', 'p.PAYMENT_PERIOD_ID', '=', 'payment_period.ID')
            ->join('payment_invoices as pn', 'pn.PAYMENT_ID', '=', 'p.ID')
            ->join('invoice as i', 'i.ID', '=', 'pn.INVOICE_ID')
            ->join('philhealth as ph', 'ph.INVOICE_ID', '=', 'i.ID')
            ->join('philhealth_prof_fee as pf', 'pf.PHIC_ID', '=', 'ph.ID')
            ->join('bill as b', 'b.ID', '=', 'pf.BILL_ID')
            ->join('contact as c', 'c.ID', '=', 'b.VENDOR_ID')
            ->where('payment_period.LOCATION_ID', '=', $LOCATION_ID)
            ->where('payment_period.ID', '=', $PAYMENT_PERIOD_ID)
            ->where('b.VENDOR_ID', '=', $DOCTOR_ID)
            ->where('b.STATUS', '=', 15)
            ->where('p.STATUS', '=', 15)
            ->first();

        if ($result) {

            return (float) $result->TOTAL ?? 0.00;
        }

        return 0.00;
    }

    public function getDoctorFeeRemainingBalance(int $LOCATION_ID, string $DATE, int $DOCTOR_ID): float
    {
        $result = PaymentPeriod::query()
            ->select(
                [
                    DB::raw('IFNULL(SUM(b.AMOUNT),0) as TOTAL'),
                ]
            )
            ->join('payment as p', 'p.PAYMENT_PERIOD_ID', '=', 'payment_period.ID')
            ->join('payment_invoices as pn', 'pn.PAYMENT_ID', '=', 'p.ID')
            ->join('invoice as i', 'i.ID', '=', 'pn.INVOICE_ID')
            ->join('philhealth as ph', 'ph.INVOICE_ID', '=', 'i.ID')
            ->join('philhealth_prof_fee as pf', 'pf.PHIC_ID', '=', 'ph.ID')
            ->join('bill as b', 'b.ID', '=', 'pf.BILL_ID')
            ->join('contact as c', 'c.ID', '=', 'b.VENDOR_ID')
            ->where('payment_period.LOCATION_ID', '=', $LOCATION_ID)
            ->where('payment_period.DATE_TO', '<', $DATE)
            ->where('b.VENDOR_ID', '=', $DOCTOR_ID)
            ->where('b.STATUS', '=', 15)
            ->where('p.STATUS', '=', 15)
            ->first();

        if ($result) {
            return (float) $result->TOTAL ?? 0.00;
        }

        return 0.00;
    }

}
