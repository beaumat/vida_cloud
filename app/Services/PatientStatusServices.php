<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PatientStatusServices
{
    private $itemServices;
    private $dateServices;
    public function __construct(ItemServices $itemServices, DateServices $date)
    {
        $this->itemServices = $itemServices;
        $this->dateServices = $date;
    }
    public function getList(int $month, int $year)
    {




        return DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("(select count(*)  from contact where contact.TYPE=3 and month(contact.DATE_ADMISSION) = '$month' and year(contact.DATE_ADMISSION) = '$year' and contact.LOCATION_ID = location.ID ) as `NEW` "),
                DB::raw("(select count(*)  from contact inner join patient_confinement on patient_confinement.patient_id = contact.ID where contact.TYPE=3 and month(patient_confinement.DATE_START) = '$month' and year(patient_confinement.DATE_START) = '$year' and contact.LOCATION_ID = location.ID ) as `CONFINEMENT` "),
                DB::raw("(select count(*)  from contact inner join patient_transfer on patient_transfer.patient_id = contact.ID where contact.TYPE=3 and month(patient_transfer.DATE_TRANSFER) = '$month' and year(patient_transfer.DATE_TRANSFER) = '$year' and contact.LOCATION_ID = location.ID ) as `TRANSFER` "),
                DB::raw("(select count(*)  from contact where contact.TYPE=3 and month(contact.DATE_EXPIRED) = '$month' and year(contact.DATE_EXPIRED) = '$year' and contact.LOCATION_ID = location.ID ) as `EXPIRED` "),
                DB::raw('(select count(*)  from contact where contact.TYPE = 3 and contact.INACTIVE = 0 and contact.LOCATION_ID = location.ID) as ACTIVE')

            ])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();
    }

    public function getTreatmentSummaryList(int $month, int $year)
    {

        $current = $month - 1;
        if ($current == 0) {
            $prev_year = $year - 1;
            $prev_month = 12;

        } else {
            $prev_month = $current;
            $prev_year = $year;

        }

        $PHIC_ITEM_ID = $this->itemServices->PHIC_ITEM_ID;
        $PRIMING_ITEM_ID = $this->itemServices->PRIMING_ITEM_ID;

        $result = DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PHIC_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$month' and year(service_charges.DATE) = '$year' and service_charges.WALK_IN = '0'  ) as TOTAL_PHILHEALTH"),
                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PRIMING_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$month' and year(service_charges.DATE) = '$year' and service_charges.WALK_IN = '0'  ) as TOTAL_PRIMING"),
                DB::raw("( select count(service_charges.ID) from service_charges  where not exists(	select service_charges_items.`ID` from service_charges_items where (service_charges_items.ITEM_ID = '$PHIC_ITEM_ID'  OR  service_charges_items.`ITEM_ID` = '$PRIMING_ITEM_ID') and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$month' and year(service_charges.DATE) = '$year' and service_charges.WALK_IN = '0' ) as TOTAL_REGULAR"),

                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PHIC_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$prev_month' and year(service_charges.DATE) = '$prev_year' and service_charges.WALK_IN = '0'  ) as PREV_TOTAL_PHILHEALTH"),
                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PRIMING_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$prev_month' and year(service_charges.DATE) = '$prev_year' and service_charges.WALK_IN = '0'  ) as PREV_TOTAL_PRIMING"),
                DB::raw("( select count(service_charges.ID) from service_charges  where not exists(	select service_charges_items.`ID` from service_charges_items where (service_charges_items.ITEM_ID = '$PHIC_ITEM_ID'  OR  service_charges_items.`ITEM_ID` = '$PRIMING_ITEM_ID') and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$prev_month' and year(service_charges.DATE) = '$prev_year' and service_charges.WALK_IN = '0' ) as PREV_TOTAL_REGULAR")
            ])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();

        return $result;
    }

    public function getPhilheatlh()
    {
        $currentDays = $this->dateServices->NowDate();
        $result = DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("(select philhealth.DATE from philhealth where philhealth.LOCATION_ID = location.ID order by philhealth.RECORDED_ON desc limit 1) as LAST_RECORDED "),
                DB::raw("(select count(*) from philhealth where philhealth.LOCATION_ID = location.ID and isnull(philhealth.`INVOICE_ID`) = true) as NO_TRANSMIT "),
                DB::raw("(SELECT DATEDIFF('$currentDays',philhealth.`DATE_ADMITTED`) FROM philhealth WHERE philhealth.`LOCATION_ID` = location.`ID` AND isnull(philhealth.`INVOICE_ID`) = true and philhealth.`DATE_ADMITTED` <= '$currentDays'  ORDER BY philhealth.`DATE_ADMITTED` LIMIT 1 ) AS DUE "),
                DB::raw("(select count(*) from philhealth where philhealth.LOCATION_ID = location.ID and isnull(philhealth.`INVOICE_ID`) = false and philhealth.PAYMENT_AMOUNT = 0) as NOT_PAID "),
            ])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();

        return $result;
    }

    public function getDoctorPF()
    {

        return DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("(select bill.DATE from bill inner join contact on contact.ID = bill.VENDOR_ID inner join contact_type_map on contact_type_map.ID = contact.TYPE where contact.TYPE = 4 and  bill.LOCATION_ID = location.ID order by bill.RECORDED_ON desc limit 1) as LAST_RECORDED "),
                DB::raw("(select sum(bill.BALANCE_DUE) from bill inner join contact on contact.ID = bill.VENDOR_ID inner join contact_type_map on contact_type_map.ID = contact.TYPE where contact.TYPE = 4 AND  bill.LOCATION_ID = location.ID and bill.BALANCE_DUE > 0) as TOTAL_BALANCE "),
                DB::raw("(select count(*) from bill inner join contact on contact.ID = bill.VENDOR_ID inner join contact_type_map on contact_type_map.ID = contact.TYPE where contact.TYPE = 4 AND  bill.LOCATION_ID = location.ID and bill.BALANCE_DUE > 0) as NO_BILL_NOT_PAID "),
                DB::raw("(select count(*) from `check` inner join contact on contact.ID = `check`.PAY_TO_ID inner join contact_type_map on contact_type_map.ID = contact.TYPE where contact.TYPE = 4  AND `check`.LOCATION_ID = location.ID  AND (`check`.STATUS = 0 or `check`.STATUS = 16 )) as NOT_PAID "),
            ])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();
    }
    public function getSalesColleciton(int $month, int $year)
    {
        $result = DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("(select sum(AMOUNT) from service_charges  where service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$month' and year(service_charges.DATE) = '$year') as SERVICE_CHARGES_TOTAL"),
                DB::raw("(select sum(AMOUNT) from sales_receipt  where sales_receipt.LOCATION_ID = location.ID and sales_receipt.STATUS = 15 and month(sales_receipt.DATE) = '$month' and year(sales_receipt.DATE) = '$year') as SALES_RECEIPT_TOTAL"),
                DB::raw("(select sum(AMOUNT) from invoice where invoice.LOCATION_ID = location.ID and invoice.STATUS = 15 and month(invoice.DATE) = '$month' and year(invoice.DATE) = '$year') as INVOICE_TOTAL"),
                DB::raw("(select sum(AMOUNT) from payment where payment.LOCATION_ID = location.ID and payment.STATUS = 15 and month(payment.DATE) = '$month' and year(payment.DATE) = '$year') as PAYMENT_TOTAL"),
            ])
            ->where('INACTIVE', '0')
            ->get();

        return $result;
    }
    public function getReceivableAging()
    {
        $AS_OF_DATE = $this->dateServices->NowDate();

        $result = DB::table('location as l')
            ->select([
                'l.ID as LOCATION_ID',
                'l.NAME as NAME',
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) <= 0 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_CURRENT "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 1 AND 30 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_1_30 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 31 AND 60 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_31_60 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 61 AND 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_61_90 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) > 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_90_OVER "),
                DB::raw(" SUM(i.BALANCE_DUE) AS BALANCE"),
            ])
            ->leftJoin('invoice as i', function ($join) {
                $join->on('i.LOCATION_ID', '=', 'l.ID');
            })
            ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
            ->where('c.INACTIVE', '=', 0)
            ->whereIn('c.TYPE', [1, 3])
            ->where('i.BALANCE_DUE', '>', 0)
            ->groupBy('l.ID', 'l.NAME')
            ->get();

        return $result;
    }
    public function getPayableAging()
    {

        $AS_OF_DATE = $this->dateServices->NowDate();

        $result = DB::table('location as l')
            ->select([
                'l.ID as LOCATION_ID',
                'l.NAME as NAME',
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) <= 0 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_CURRENT "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 1 AND 30 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_1_30 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 31 AND 60 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_31_60 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 61 AND 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_61_90 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) > 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_90_OVER "),
                DB::raw(" SUM(i.BALANCE_DUE) AS BALANCE"),
            ])
            ->leftJoin('bill as i', function ($join) {
                $join->on('i.LOCATION_ID', '=', 'l.ID');
            })
            ->join('contact as c', 'c.ID', '=', 'i.VENDOR_ID')
            ->where('c.INACTIVE', '=', 0)
            ->whereIn('c.TYPE', [0, 4])
            ->where('i.BALANCE_DUE', '>', 0)
            ->groupBy('l.ID', 'l.NAME')
            ->get();

        return $result;
    }
}