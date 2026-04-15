<?php
namespace App\Services;

use App\Models\Contacts;
use App\Models\Items;
use App\Models\PaymentMethods;
use Illuminate\Support\Facades\DB;

class PatientReportServices
{
    private $itemServices;
    private $dateServices;
    public function __construct(ItemServices $itemServices, DateServices $dateServices)
    {
        $this->itemServices = $itemServices;
        $this->dateServices = $dateServices;
    }

    /**
     * @param array $patientData
     */
   public function generateSalesReportData2(string $scFrom, string $scTo, int  $locatoinId, array  $patientData = [], array $itemData = [], array $methodData = []): object
    {

        $results = DB::table('service_charges_items as sci')
            ->select([
                'sc.ID as SC_ID',
                'sc.CODE as SC_CODE',
                'sc.DATE as SC_DATE',
                'sci.ID as SC_ITEM_REF_ID',
                'sci.AMOUNT as SC_AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.CODE as ITEM_CODE',
                'i.DESCRIPTION as ITEM_NAME',
                'pp.ID as PP_ID',
                DB::raw('IFNULL(pp.RECEIPT_DATE,pp.DATE)  as PP_DATE'),
                DB::raw('IFNULL(pp.RECEIPT_REF_NO,pp.CODE) as PP_CODE'),
                'pm.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('IFNULL(pp.AMOUNT, 0) as PP_DEPOSIT'),
                DB::raw(' IF(ISNULL(pp.AMOUNT),0, IFNULL(ppc.AMOUNT_APPLIED, 0))  as PP_PAID'),
                'l.NAME as LOCATION_NAME',
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                'pp.PAYMENT_METHOD_ID'
            ])
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.SERVICE_CHARGES_ITEM_ID', '=', 'sci.ID')
            ->leftJoin('patient_payment as pp', function ($join) {
                $join->on('pp.ID', '=', 'ppc.PATIENT_PAYMENT_ID')
                    ->on('pp.LOCATION_ID', '=', 'sc.LOCATION_ID')
                    ->on('pp.DATE', '<=', 'sc.DATE');
            })
            ->leftJoin('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->whereBetween('sc.DATE', [$scFrom, $scTo])
            ->when($locatoinId > 0, function ($query) use (&$locatoinId) {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->when($patientData, function ($query) use (&$patientData) {
                $array = $patientData;
                $query->whereIn('sc.PATIENT_ID', $array);
            })
            ->when($itemData, function ($query) use (&$itemData) {
                $array = $itemData;
                $query->whereIn('sci.ITEM_ID', $array);
            })
            ->when($methodData, function ($query) use (&$methodData) {
                $array = $methodData;
                $query->whereIn('pp.PAYMENT_METHOD_ID', $array);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID')
            ->get();

        return $results;
    }
    public function generatePrevCollection2(string $scFrom, string $scTo, int  $locatoinId, array  $patientData = [], array $itemData = [], array $methodData = []): object
    {
        $results = DB::table('service_charges_items as sci')
            ->select([
                'sc.ID as SC_ID',
                'sc.CODE as SC_CODE',
                'sc.DATE as SC_DATE',
                'sci.ID as SC_ITEM_REF_ID',
                'sci.AMOUNT as SC_AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.CODE as ITEM_CODE',
                'i.DESCRIPTION as ITEM_NAME',
                'pp.ID as PP_ID',
                DB::raw('IFNULL(pp.RECEIPT_DATE,pp.DATE)  as PP_DATE'),
                DB::raw('IFNULL(pp.RECEIPT_REF_NO,pp.CODE) as PP_CODE'),
                'pm.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('IFNULL(pp.AMOUNT, 0) as PP_DEPOSIT'),
                DB::raw('IFNULL(ppc.AMOUNT_APPLIED, 0) as PP_PAID'),
                'l.NAME as LOCATION_NAME',
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                'pp.PAYMENT_METHOD_ID'
            ])
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.SERVICE_CHARGES_ITEM_ID', '=', 'sci.ID')
            ->leftJoin('patient_payment as pp', function ($join) {
                $join->on('pp.ID', '=', 'ppc.PATIENT_PAYMENT_ID')
                    ->on('pp.LOCATION_ID', '=', 'sc.LOCATION_ID');
            })
            ->leftJoin('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->where('pp.PAYMENT_METHOD_ID', 1)
            ->where(function ($query) use (&$scFrom, &$scTo) {
                $query->whereBetween('pp.DATE', [$scFrom, $scTo])
                    ->whereNotBetween('sc.DATE', [$scFrom, $scTo]);
            })
            ->when($locatoinId > 0, function ($query) use (&$locatoinId): void {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->when($patientData, function ($query) use (&$patientData): void {
                $array = $patientData;
                $query->whereIn('sc.PATIENT_ID', $array);
            })
            ->when($itemData, function ($query) use (&$itemData): void {
                $array = $itemData;
                $query->whereIn('sci.ITEM_ID', $array);
            })
            ->when($methodData, function ($query) use (&$methodData) {
                $array = $methodData;
                $query->whereIn('pp.PAYMENT_METHOD_ID', $array);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID')
            ->get();

        return $results;
    }
    public function generateSalesReportData(string $scFrom, string $scTo, int $locatoinId, array $patientData = [], array $itemData = [], array $methodData = []): object
    {

        $scResult = DB::table('service_charges_items as sci')
            ->select([
                'sc.ID as ID',
                'sc.CODE as CODE',
                'sc.DATE as DATE',
                'sci.ID as ITEM_REF_ID',
                'sci.LINE_NO',
                'sci.AMOUNT as AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.DESCRIPTION as ITEM_NAME',
                'l.NAME as LOCATION_NAME',
                DB::raw("(select SUM(ppc.AMOUNT_APPLIED) FROM PATIENT_PAYMENT_CHARGES AS ppc inner join  PATIENT_PAYMENT as pp on pp.ID = ppc.PATIENT_PAYMENT_ID where pp.DATE < '$scFrom' and pp.LOCATION_ID = sc.LOCATION_ID and ppc.SERVICE_CHARGES_ITEM_ID = sci.ID ) as PREVIOUS_CREDIT "), // Placeholder for previous credit
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                'item_class.DESCRIPTION as CLASS_NAME',
                'sci.QUANTITY',

            ])
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('item_sub_class', 'item_sub_class.ID', '=', 'i.SUB_CLASS_ID')
            ->leftJoin('item_class', 'item_class.ID', '=', 'item_sub_class.CLASS_ID')
            ->whereBetween('sc.DATE', [$scFrom, $scTo])
            ->when($locatoinId > 0, function ($query) use (&$locatoinId) {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->when($patientData, function ($query) use (&$patientData) {
                $array = $patientData;
                $query->whereIn('sc.PATIENT_ID', $array);
            })
            ->when($itemData, function ($query) use (&$itemData) {
                $array = $itemData;
                $query->whereIn('sci.ITEM_ID', $array);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID');

        $paymentResult = DB::table('patient_payment as pp')
            ->select([
                'pp.ID as ID',
                'pp.CODE as CODE',
                'pp.DATE as DATE',
                DB::raw('0 as ITEM_REF_ID'),
                DB::raw('999 as LINE_NO'),
                'pp.AMOUNT as AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                DB::raw("CONCAT(pm.DESCRIPTION ,' : ',(SELECT GROUP_CONCAT( CONCAT( CONVERT(i.DESCRIPTION USING utf8mb4), ' : ', FORMAT(sci.AMOUNT, 2) ) ORDER BY sci.LINE_NO ASC SEPARATOR '| ' ) AS items_summary FROM patient_payment_charges AS ppc INNER JOIN service_charges_items AS sci ON sci.ID = ppc.SERVICE_CHARGES_ITEM_ID INNER JOIN item AS i ON i.ID = sci.ITEM_ID WHERE ppc.PATIENT_PAYMENT_ID = pp.ID)) as ITEM_NAME"),
                'l.NAME as LOCATION_NAME',
                DB::raw("0 as PREVIOUS_CREDIT "), // Placeholder for previous credit
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                DB::raw("'' as CLASS_NAME"),
                DB::raw("'' as QUANTITY"),
            ])
            ->join('location as l', 'l.ID', '=', 'pp.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'pp.PATIENT_ID')
            ->join('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->where('pm.ID', '<>', 91)
            ->when($patientData, function ($query) use (&$patientData) {
                $array = $patientData;
                $query->whereIn('pp.PATIENT_ID', $array);
            })
            ->when($methodData, function ($query) use (&$methodData) {
                $array = $methodData;
                $query->whereIn('pp.PAYMENT_METHOD_ID', $array);
            })
            ->whereBetween('pp.DATE', [$scFrom, $scTo])
            ->when($locatoinId > 0, function ($query) use (&$locatoinId) {
                $query->where('pp.LOCATION_ID', $locatoinId);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('pp.CODE')
            ->orderBy('pp.ID');

        $union = $scResult->unionAll($paymentResult);

        $finalQuery = DB::query()
            ->fromSub($union, 'combined')
            ->orderBy('PATIENT_NAME')
            ->orderBy('DATE')
            ->orderBy('LINE_NO')
            ->orderBy('CODE')
            ->get();

        return $finalQuery;
    }

    public function getPreviousCollection(string $scFrom, string $scTo, int $locatoinId, array $patientData = [], array $itemData = [], array $methodData = []): object
    {

        $results = DB::table('service_charges_items as sci')
            ->select([
                'sc.ID as SC_ID',
                'sc.CODE as SC_CODE',
                'sc.DATE as SC_DATE',
                'sci.ID as SC_ITEM_REF_ID',
                'sci.AMOUNT as SC_AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.CODE as ITEM_CODE',
                'i.DESCRIPTION as ITEM_NAME',
                'pp.ID as PP_ID',
                DB::raw('IFNULL(pp.RECEIPT_DATE,pp.DATE)  as PP_DATE'),
                DB::raw('IFNULL(pp.RECEIPT_REF_NO,pp.CODE) as PP_CODE'),
                'pm.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('IFNULL(pp.AMOUNT, 0) as PP_DEPOSIT'),
                DB::raw('IFNULL(ppc.AMOUNT_APPLIED, 0) as PP_PAID'),
                'l.NAME as LOCATION_NAME',
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                'pp.PAYMENT_METHOD_ID',
            ])
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.SERVICE_CHARGES_ITEM_ID', '=', 'sci.ID')
            ->leftJoin('patient_payment as pp', function ($join) {
                $join->on('pp.ID', '=', 'ppc.PATIENT_PAYMENT_ID')
                    ->on('pp.LOCATION_ID', '=', 'sc.LOCATION_ID');
            })
            ->leftJoin('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->where('pp.DATE', '<', $scFrom)
            ->where('pm.ID', '<>', 91)
            ->whereBetween('sc.DATE', [$scFrom, $scTo])
            ->when($locatoinId > 0, function ($query) use (&$locatoinId): void {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->when($patientData, function ($query) use (&$patientData): void {
                $array = $patientData;
                $query->whereIn('sc.PATIENT_ID', $array);
            })
            ->when($itemData, function ($query) use (&$itemData): void {
                $array = $itemData;
                $query->whereIn('sci.ITEM_ID', $array);
            })
            ->when($methodData, function ($query) use (&$methodData) {
                $array = $methodData;
                $query->whereIn('pp.PAYMENT_METHOD_ID', $array);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID')
            ->get();

        return $results;
    }
    public function generatePrevCollection(string $scFrom, string $scTo, int $locatoinId, array $patientData = [], array $itemData = [], array $methodData = [])
    {
        $isWhole = (bool) $this->dateServices->isWholeMonth($scFrom, $scTo);

        if ($isWhole) {
            return [];
        }

        $results = DB::table('service_charges_items as sci')
            ->select([
                'sc.ID as SC_ID',
                'sc.CODE as SC_CODE',
                'sc.DATE as SC_DATE',
                'sci.ID as SC_ITEM_REF_ID',
                'sci.AMOUNT as SC_AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.CODE as ITEM_CODE',
                'i.DESCRIPTION as ITEM_NAME',
                'pp.ID as PP_ID',
                DB::raw('IFNULL(pp.RECEIPT_DATE,pp.DATE)  as PP_DATE'),
                DB::raw('IFNULL(pp.RECEIPT_REF_NO,pp.CODE) as PP_CODE'),
                'pm.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('IFNULL(pp.AMOUNT, 0) as PP_DEPOSIT'),
                DB::raw('IFNULL(ppc.AMOUNT_APPLIED, 0) as PP_PAID'),
                'l.NAME as LOCATION_NAME',
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                'pp.PAYMENT_METHOD_ID',
            ])
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.SERVICE_CHARGES_ITEM_ID', '=', 'sci.ID')
            ->leftJoin('patient_payment as pp', function ($join) {
                $join->on('pp.ID', '=', 'ppc.PATIENT_PAYMENT_ID')
                    ->on('pp.LOCATION_ID', '=', 'sc.LOCATION_ID');
            })
            ->leftJoin('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->where('pp.PAYMENT_METHOD_ID', 1)
            ->where(function ($query) use (&$scFrom, &$scTo) {
                $query->whereBetween('pp.DATE', [$scFrom, $scTo])
                    ->whereNotBetween('sc.DATE', [$scFrom, $scTo]);
            })
            ->when($locatoinId > 0, function ($query) use (&$locatoinId): void {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->when($patientData, function ($query) use (&$patientData): void {
                $array = $patientData;
                $query->whereIn('sc.PATIENT_ID', $array);
            })
            ->when($itemData, function ($query) use (&$itemData): void {
                $array = $itemData;
                $query->whereIn('sci.ITEM_ID', $array);
            })
            ->when($methodData, function ($query) use (&$methodData) {
                $array = $methodData;
                $query->whereIn('pp.PAYMENT_METHOD_ID', $array);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID')
            ->get();

        return $results;
    }
    public function getItemListViaReport(int $LOCATION_ID, string $DATE_FROM, string $DATE_TO): object
    {

        $result = Items::query()
            ->select(['ID', 'DESCRIPTION'])
            ->whereExists(function ($query) use (&$LOCATION_ID, &$DATE_FROM, &$DATE_TO) {
                $query->select(DB::raw(1))
                    ->from('service_charges as s')
                    ->join('service_charges_items as ci', function ($join) {
                        $join->on('ci.SERVICE_CHARGES_ID', '=', 's.ID')
                            ->on('ci.ITEM_ID', '=', 'item.ID');
                    })
                    ->where('s.LOCATION_ID', '=', $LOCATION_ID)
                    ->whereBetween('s.DATE', [$DATE_FROM, $DATE_TO]);
            })
            ->orderBy('DESCRIPTION', 'asc')
            ->get();

        return $result;
    }
    public function getMethodListViaReport(int $LOCATION_ID, string $DATE_FROM, string $DATE_TO): object
    {

        $result = PaymentMethods::query()
            ->select(['payment_method.ID', 'payment_method.DESCRIPTION'])
            ->join('patient_payment as pp', 'pp.PAYMENT_METHOD_ID', '=', 'payment_method.ID')
            ->join('patient_payment_charges as ppc', 'ppc.PATIENT_PAYMENT_ID', '=', 'pp.ID')
            ->whereExists(function ($query) use (&$LOCATION_ID, &$DATE_FROM, &$DATE_TO) {
                $query->select(DB::raw(1))
                    ->from('service_charges as s')
                    ->join('service_charges_items as ci', function ($join) {
                        $join->on('ci.SERVICE_CHARGES_ID', '=', 's.ID')
                            ->on('ci.ID', '=', 'ppc.SERVICE_CHARGES_ITEM_ID');
                    })
                    ->where('s.LOCATION_ID', '=', $LOCATION_ID)
                    ->whereBetween('s.DATE', [$DATE_FROM, $DATE_TO]);
            })
            ->orderBy('DESCRIPTION', 'asc')
            ->groupBy(['payment_method.ID', 'payment_method.DESCRIPTION'])
            ->get();

        return $result;
    }
    public function getMonthlyTreatment(int $year, int $month, array $dayList = [], array $patient = [], int $LocationId): object
    {
        $PHIC_ITEM_ID    = $this->itemServices->PHIC_ITEM_ID;
        $PRIMING_ITEM_ID = $this->itemServices->PRIMING_ITEM_ID;

        foreach ($dayList as $day) {
            $coldate         = date('d', strtotime($day));
            $sql             = "(select if( i.ITEM_ID  = $PHIC_ITEM_ID , 1, if(i.ITEM_ID = $PRIMING_ITEM_ID , 2 , if(i.ITEM_ID <> $PHIC_ITEM_ID and i.ITEM_ID <> $PRIMING_ITEM_ID, 3, 0) ) ) from hemodialysis as d  join service_charges as s on (s.DATE = d.DATE and s.LOCATION_ID = d.LOCATION_ID and s.PATIENT_ID = d.CUSTOMER_ID)  inner join service_charges_items as i on i.SERVICE_CHARGES_ID = s.ID inner join item as t on t.ID = i.ITEM_ID where d.LOCATION_ID ='$LocationId' and d.DATE = '$day' and d.CUSTOMER_ID = contact.ID and d.STATUS_ID = 2  order by t.TYPE desc limit 1 ) as '$coldate'";
            $selectArrayTR[] = DB::raw($sql);
        }

        $results = Contacts::query()
            ->select($selectArrayTR)
            ->addSelect([
                DB::raw("CONCAT(contact.LAST_NAME, ', ', contact.FIRST_NAME, ' .', LEFT(contact.MIDDLE_NAME, 1), IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as PATIENT_NAME"),
            ])
            ->whereExists(function ($query) use (&$year, &$month, &$LocationId) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis as h')
                    ->whereColumn('h.CUSTOMER_ID', '=', 'contact.ID')
                    ->where('h.STATUS_ID', '=', 2)
                    ->where('h.LOCATION_ID', '=', $LocationId)
                    ->whereMonth('h.DATE', '=', $month)
                    ->whereYear('h.DATE', '=', $year);
            })
            ->orderBy('contact.LAST_NAME', 'asc')
            ->get();

        return $results;
    }
    public function getInventoryList(string $DATE_FROM, string $DATE_TO, int $LOCATION_ID, int $ITEM_ID = 0)
    {

        $result = DB::table('hemodialysis_items as hemo_item')
            ->select([
                'hemo.DATE',
                'i.DESCRIPTION as ITEM_NAME',
                'i.CODE as ITEM_CODE',
                'hemo_item.QUANTITY',
                'uom.NAME as UNIT',
                'hemo_item.IS_POST as POST',
                DB::raw('0 as WALKIN'),
                'l.NAME as LOCATION_NAME',
                'hemo.ID as HEMO_ID',
                'hemo.CODE as REFERENCE',
                DB::raw("CONCAT(contact.LAST_NAME, ', ', contact.FIRST_NAME, ' .', LEFT(contact.MIDDLE_NAME, 1), IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as PATIENT_NAME"),
            ])
            ->join('hemodialysis as hemo', 'hemo_item.HEMO_ID', '=', 'hemo.ID')
            ->join('contact', 'hemo.CUSTOMER_ID', '=', 'contact.ID')
            ->join('item as i', 'hemo_item.ITEM_ID', '=', 'i.ID')
            ->leftJoin('unit_of_measure as uom', 'uom.ID', '=', 'hemo_item.UNIT_ID')
            ->join('location as l', 'l.ID', '=', 'hemo.LOCATION_ID')
            ->whereIn('i.TYPE', [0, 1])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('hemo.LOCATION_ID', $LOCATION_ID);
            })
            ->when($ITEM_ID > 0, function ($query) use (&$ITEM_ID) {
                $query->where('hemo_item.ITEM_ID', $ITEM_ID);
            })
            ->whereBetween('hemo.DATE', [$DATE_FROM, $DATE_TO])
            ->where('hemo.STATUS_ID', 2)
            ->orderBy('hemo.DATE')
            ->orderBy('hemo.CODE')
            ->get();

        return $result;
    }
    public function getInventoryListFiter(string $DATE_FROM, string $DATE_TO, int $LOCATION_ID, int $ITEM_ID = 0)
    {

        $result = DB::table('hemodialysis_items as hemo_item')
            ->select([
                'hemo.DATE',
                'i.DESCRIPTION as ITEM_NAME',
                'i.CODE as ITEM_CODE',
                'hemo_item.QUANTITY',
                'uom.NAME as UNIT',
                'hemo_item.IS_POST as POST',
                DB::raw('0 as WALKIN'),
                'l.NAME as LOCATION_NAME',
                'hemo.ID as HEMO_ID',
                'hemo.CODE as REFERENCE',
                DB::raw("CONCAT(contact.LAST_NAME, ', ', contact.FIRST_NAME, ' .', LEFT(contact.MIDDLE_NAME, 1), IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as PATIENT_NAME"),
            ])
            ->join('hemodialysis as hemo', 'hemo_item.HEMO_ID', '=', 'hemo.ID')
            ->join('contact', 'hemo.CUSTOMER_ID', '=', 'contact.ID')
            ->join('item as i', 'hemo_item.ITEM_ID', '=', 'i.ID')
            ->leftJoin('unit_of_measure as uom', 'uom.ID', '=', 'hemo_item.UNIT_ID')
            ->join('location as l', 'l.ID', '=', 'hemo.LOCATION_ID')
            ->whereIn('i.TYPE', [0, 1])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('hemo.LOCATION_ID', $LOCATION_ID);
            })
            ->when($ITEM_ID > 0, function ($query) use (&$ITEM_ID) {
                $query->where('hemo_item.ITEM_ID', $ITEM_ID);
            })
            ->whereBetween('hemo.DATE', [$DATE_FROM, $DATE_TO])
            ->where('hemo.STATUS_ID', 2)
            ->get();

        return $result;
    }
}
